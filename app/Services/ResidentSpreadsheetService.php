<?php

namespace App\Services;

use App\Models\Resident;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;
use ZipArchive;

class ResidentSpreadsheetService
{
    /**
     * Baca file spreadsheet (XLSX atau CSV) menjadi array baris data.
     */
    public function readSpreadsheet(string $filePath, string $extension): array
    {
        $ext = strtolower($extension);

        if ($ext === 'csv' || $ext === 'txt') {
            return $this->readCsv($filePath);
        }

        if ($ext === 'xlsx') {
            return $this->readXlsx($filePath);
        }

        throw new \InvalidArgumentException('Format file tidak didukung. Harap unggah file .xlsx atau .csv');
    }

    /**
     * Baca file CSV dengan deteksi delimiter dan UTF-8 BOM.
     */
    protected function readCsv(string $filePath): array
    {
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \RuntimeException('Tidak dapat membuka file CSV.');
        }

        $rows = [];
        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);
            return [];
        }

        // Hapus UTF-8 BOM jika ada
        $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);

        // Deteksi delimiter: koma atau titik koma
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        // Parse baris pertama
        $rows[] = str_getcsv($firstLine, $delimiter);

        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rows[] = $data;
        }

        fclose($handle);
        return $rows;
    }

    /**
     * Baca file XLSX menggunakan ZipArchive dan SimpleXMLElement bawaan PHP.
     * ponytail: memanfaatkan pustaka bawaan PHP tanpa dependensi Composer berukuran besar
     */
    protected function readXlsx(string $filePath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \RuntimeException('File XLSX tidak valid atau rusak.');
        }

        // 1. Baca shared strings
        $sharedStrings = [];
        $sharedXmlContent = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXmlContent !== false) {
            $sharedXml = new SimpleXMLElement($sharedXmlContent);
            foreach ($sharedXml->si as $si) {
                if (isset($si->t)) {
                    $sharedStrings[] = (string) $si->t;
                } elseif (isset($si->r)) {
                    $text = '';
                    foreach ($si->r as $r) {
                        $text .= (string) $r->t;
                    }
                    $sharedStrings[] = $text;
                } else {
                    $sharedStrings[] = '';
                }
            }
        }

        // 2. Baca worksheet pertama (sheet1.xml)
        $sheetXmlContent = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXmlContent === false) {
            $zip->close();
            throw new \RuntimeException('Worksheet tidak ditemukan di dalam file XLSX.');
        }

        $sheetXml = new SimpleXMLElement($sheetXmlContent);
        $zip->close();

        $rows = [];
        if (!isset($sheetXml->sheetData->row)) {
            return [];
        }

        foreach ($sheetXml->sheetData->row as $row) {
            $rowData = [];
            $maxCol = 0;

            foreach ($row->c as $cell) {
                $ref = (string) $cell['r'];
                // Dapatkan indeks kolom (A -> 0, B -> 1, Z -> 25, AA -> 26, dst)
                preg_match('/^([A-Z]+)(\d+)$/', $ref, $matches);
                $colIndex = 0;
                if (!empty($matches[1])) {
                    $colIndex = $this->columnLetterToIndex($matches[1]);
                }

                $value = '';
                $type = (string) $cell['t'];

                if ($type === 's') {
                    // Shared string index
                    $sIndex = (int) $cell->v;
                    $value = $sharedStrings[$sIndex] ?? '';
                } elseif ($type === 'inlineStr' && isset($cell->is->t)) {
                    $value = (string) $cell->is->t;
                } elseif (isset($cell->v)) {
                    $value = (string) $cell->v;
                }

                $rowData[$colIndex] = trim($value);
                if ($colIndex > $maxCol) {
                    $maxCol = $colIndex;
                }
            }

            // Normalisasi array agar urut dari kolom 0 hingga maxCol
            $normalizedRow = [];
            for ($i = 0; $i <= $maxCol; $i++) {
                $normalizedRow[$i] = $rowData[$i] ?? '';
            }

            $rows[] = $normalizedRow;
        }

        return $rows;
    }

    /**
     * Konversi huruf kolom Excel (A, B, AA) ke index berbasis 0.
     */
    protected function columnLetterToIndex(string $col): int
    {
        $col = strtoupper($col);
        $index = 0;
        $len = strlen($col);
        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($col[$i]) - ord('A') + 1);
        }
        return $index - 1;
    }

    /**
     * Validasi baris-baris spreadsheet dan hasilkan struktur preview.
     */
    public function previewData(array $rawRows): array
    {
        if (empty($rawRows)) {
            return [
                'success' => false,
                'message' => 'File kosong atau tidak memiliki data.',
            ];
        }

        // Cari baris header
        $headerRowIndex = null;
        $colMap = [];

        foreach ($rawRows as $idx => $row) {
            $map = $this->matchHeaderRow($row);
            if ($map !== null) {
                $headerRowIndex = $idx;
                $colMap = $map;
                break;
            }
        }

        if ($headerRowIndex === null) {
            return [
                'success' => false,
                'message' => 'Format header tidak sesuai. Header wajib mencakup kolom: NIK, Nama Lengkap (kolom Alamat, RT, RW, Status bersifat opsional).',
            ];
        }

        $existingNiks = Resident::pluck('nik')->all();
        $existingNikLookup = array_flip($existingNiks);

        $parsedRows = [];
        $seenNiksInFile = [];

        $validCount = 0;
        $duplicateCount = 0;
        $invalidCount = 0;

        $totalRows = count($rawRows) - ($headerRowIndex + 1);

        for ($i = $headerRowIndex + 1; $i < count($rawRows); $i++) {
            $row = $rawRows[$i];

            // Abaikan baris yang seluruhnya kosong
            $isEmptyRow = true;
            foreach ($row as $val) {
                if (trim((string) $val) !== '') {
                    $isEmptyRow = false;
                    break;
                }
            }
            if ($isEmptyRow) {
                continue;
            }

            $nik = trim((string) ($row[$colMap['nik']] ?? ''));
            // Buang notasi eksponensial jika Excel sempat mengubah ke format number
            if (preg_match('/^(\d+)\.?\d*e\+(\d+)$/i', $nik, $m)) {
                $nik = sprintf('%.0f', (float) $nik);
            }

            $name = trim((string) ($row[$colMap['name']] ?? ''));
            $address = isset($colMap['address']) ? trim((string) ($row[$colMap['address']] ?? '')) : '';
            $rt = isset($colMap['rt']) ? trim((string) ($row[$colMap['rt']] ?? '')) : '';
            $rw = isset($colMap['rw']) ? trim((string) ($row[$colMap['rw']] ?? '')) : '';
            $statusRaw = isset($colMap['status']) ? trim((string) ($row[$colMap['status']] ?? '')) : '';

            $status = 'aktif';
            if (strtolower($statusRaw) === 'nonaktif' || strtolower($statusRaw) === 'tidak aktif' || $statusRaw === '0') {
                $status = 'nonaktif';
            }

            $rowItem = [
                'row_number' => $i + 1,
                'nik' => $nik,
                'name' => $name,
                'address' => $address,
                'rt' => $rt,
                'rw' => $rw,
                'status' => $status,
                'status_type' => 'valid', // 'valid', 'duplicate', 'invalid'
                'reasons' => [],
            ];

            // Validasi data
            if ($nik === '') {
                $rowItem['status_type'] = 'invalid';
                $rowItem['reasons'][] = 'NIK wajib diisi';
            } elseif (!preg_match('/^\d{8,20}$/', $nik)) {
                $rowItem['status_type'] = 'invalid';
                $rowItem['reasons'][] = 'Format NIK harus berupa angka (8-20 digit)';
            }

            if ($name === '') {
                $rowItem['status_type'] = 'invalid';
                $rowItem['reasons'][] = 'Nama Lengkap wajib diisi';
            }

            // Cek duplikasi jika baris belum dianggap invalid
            if ($rowItem['status_type'] !== 'invalid') {
                if (isset($seenNiksInFile[$nik])) {
                    $rowItem['status_type'] = 'duplicate';
                    $rowItem['reasons'][] = "Duplikat dalam file dengan baris ke-{$seenNiksInFile[$nik]}";
                } elseif (isset($existingNikLookup[$nik])) {
                    $rowItem['status_type'] = 'duplicate';
                    $rowItem['reasons'][] = 'NIK sudah terdaftar dalam database';
                }
            }

            if ($nik !== '') {
                $seenNiksInFile[$nik] = $i + 1;
            }

            if ($rowItem['status_type'] === 'valid') {
                $validCount++;
            } elseif ($rowItem['status_type'] === 'duplicate') {
                $duplicateCount++;
            } else {
                $invalidCount++;
            }

            $parsedRows[] = $rowItem;
        }

        return [
            'success' => true,
            'summary' => [
                'total' => count($parsedRows),
                'valid' => $validCount,
                'duplicate' => $duplicateCount,
                'invalid' => $invalidCount,
            ],
            'rows' => $parsedRows,
        ];
    }

    /**
     * Cocokkan baris dengan nama-nama kolom template.
     */
    protected function matchHeaderRow(array $row): ?array
    {
        $nikIdx = null;
        $nameIdx = null;
        $addressIdx = null;
        $rtIdx = null;
        $rwIdx = null;
        $statusIdx = null;

        foreach ($row as $idx => $cell) {
            $norm = strtolower(trim((string) $cell));
            if ($norm === 'nik' || str_contains($norm, 'nomor induk kependudukan')) {
                $nikIdx = $idx;
            } elseif ($norm === 'nama lengkap' || $norm === 'nama' || $norm === 'nama_lengkap') {
                $nameIdx = $idx;
            } elseif ($norm === 'alamat' || $norm === 'alamat domisili') {
                $addressIdx = $idx;
            } elseif ($norm === 'rt') {
                $rtIdx = $idx;
            } elseif ($norm === 'rw') {
                $rwIdx = $idx;
            } elseif ($norm === 'status' || $norm === 'status akun') {
                $statusIdx = $idx;
            }
        }

        if ($nikIdx !== null && $nameIdx !== null) {
            return [
                'nik' => $nikIdx,
                'name' => $nameIdx,
                'address' => $addressIdx,
                'rt' => $rtIdx,
                'rw' => $rwIdx,
                'status' => $statusIdx,
            ];
        }

        return null;
    }

    /**
     * Eksekusi penyimpanan data valid ke database.
     * Menggunakan transaksi database agar konsistensi data terjamin.
     */
    public function importValidRows(array $rowsToImport): array
    {
        $importedCount = 0;
        $skippedCount = 0;
        $failedRows = [];

        DB::transaction(function () use ($rowsToImport, &$importedCount, &$skippedCount, &$failedRows) {
            $existingNiks = Resident::pluck('nik')->all();
            $existingNikLookup = array_flip($existingNiks);

            foreach ($rowsToImport as $row) {
                $nik = trim((string) ($row['nik'] ?? ''));
                $name = trim((string) ($row['name'] ?? ''));

                if ($nik === '' || $name === '') {
                    $skippedCount++;
                    $failedRows[] = [
                        'row' => $row['row_number'] ?? '-',
                        'nik' => $nik,
                        'name' => $name,
                        'reason' => 'Data wajib (NIK/Nama) kosong',
                    ];
                    continue;
                }

                if (isset($existingNikLookup[$nik])) {
                    $skippedCount++;
                    $failedRows[] = [
                        'row' => $row['row_number'] ?? '-',
                        'nik' => $nik,
                        'name' => $name,
                        'reason' => 'NIK sudah terdaftar sebelumnya (tidak ditimpa)',
                    ];
                    continue;
                }

                $status = ($row['status'] ?? 'aktif') === 'nonaktif' ? 'nonaktif' : 'aktif';

                Resident::create([
                    'nik' => $nik,
                    'name' => $name,
                    'address' => $row['address'] ?? null,
                    'rt' => $row['rt'] ?? null,
                    'rw' => $row['rw'] ?? null,
                    'status' => $status,
                    'password' => Hash::make($nik),
                    'must_change_password' => true,
                ]);

                $existingNikLookup[$nik] = true;
                $importedCount++;
            }
        });

        return [
            'success' => true,
            'imported_count' => $importedCount,
            'skipped_count' => $skippedCount,
            'failed_rows' => $failedRows,
        ];
    }

    /**
     * Buat file template XLSX resmi.
     */
    public function generateTemplateXlsxContent(): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'tmpl_');
        $zip = new ZipArchive();
        $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // xl/workbook.xml
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Data Warga" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // xl/styles.xml (Format text pada kolom NIK)
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <numFmts count="1">
    <numFmt numFmtId="49" formatCode="@"/>
  </numFmts>
  <fonts count="1">
    <font><name val="Calibri"/><sz val="11"/></font>
  </fonts>
  <fills count="2">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
  </fills>
  <borders count="1">
    <border><left/><right/><top/><bottom/><diagonal/></border>
  </borders>
  <cellStyleXfs count="1">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
  </cellStyleXfs>
  <cellXfs count="2">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    <xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>
  </cellXfs>
</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // xl/worksheets/sheet1.xml (dengan teks di baris header dan contoh data)
        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <cols>
    <col min="1" max="1" width="24" customWidth="1" style="1"/>
    <col min="2" max="2" width="28" customWidth="1"/>
    <col min="3" max="3" width="35" customWidth="1"/>
    <col min="4" max="4" width="10" customWidth="1"/>
    <col min="5" max="5" width="10" customWidth="1"/>
    <col min="6" max="6" width="16" customWidth="1"/>
  </cols>
  <sheetData>
    <row r="1">
      <c r="A1" t="inlineStr"><is><t>NIK</t></is></c>
      <c r="B1" t="inlineStr"><is><t>Nama Lengkap</t></is></c>
      <c r="C1" t="inlineStr"><is><t>Alamat</t></is></c>
      <c r="D1" t="inlineStr"><is><t>RT</t></is></c>
      <c r="E1" t="inlineStr"><is><t>RW</t></is></c>
      <c r="F1" t="inlineStr"><is><t>Status</t></is></c>
    </row>
    <row r="2">
      <c r="A2" t="inlineStr" s="1"><is><t>3205010101900001</t></is></c>
      <c r="B2" t="inlineStr"><is><t>Ahmad Hidayat</t></is></c>
      <c r="C2" t="inlineStr"><is><t>Jl. Kenanga No. 12</t></is></c>
      <c r="D2" t="inlineStr"><is><t>001</t></is></c>
      <c r="E2" t="inlineStr"><is><t>002</t></is></c>
      <c r="F2" t="inlineStr"><is><t>Aktif</t></is></c>
    </row>
    <row r="3">
      <c r="A3" t="inlineStr" s="1"><is><t>3205010202920002</t></is></c>
      <c r="B3" t="inlineStr"><is><t>Siti Rahmawati</t></is></c>
      <c r="C3" t="inlineStr"><is><t>Jl. Mawar No. 05</t></is></c>
      <c r="D3" t="inlineStr"><is><t>002</t></is></c>
      <c r="E3" t="inlineStr"><is><t>002</t></is></c>
      <c r="F3" t="inlineStr"><is><t>Aktif</t></is></c>
    </row>
  </sheetData>
</worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet);
        $zip->close();

        $content = file_get_contents($tempFile);
        @unlink($tempFile);

        return $content;
    }
}
