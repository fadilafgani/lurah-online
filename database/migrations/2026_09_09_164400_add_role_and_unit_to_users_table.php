<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Akun yang sudah ada adalah admin kelurahan; unit dibedakan lewat
            // role agar daftar akun unit tidak ikut menampilkan akun admin.
            $table->string('role')->default('admin')->after('email');
            $table->string('unit')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'unit']);
        });
    }
};
