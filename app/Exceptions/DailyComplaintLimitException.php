<?php

namespace App\Exceptions;

use Exception;

class DailyComplaintLimitException extends Exception
{
    public int $maxLimit;
    public int $usedToday;

    public function __construct(int $maxLimit, int $usedToday, string $message = "")
    {
        $this->maxLimit = $maxLimit;
        $this->usedToday = $usedToday;

        if (empty($message)) {
            $message = "Anda telah mencapai batas maksimal {$maxLimit} pengaduan untuk hari ini. Silakan mencoba kembali besok.";
        }

        parent::__construct($message);
    }
}
