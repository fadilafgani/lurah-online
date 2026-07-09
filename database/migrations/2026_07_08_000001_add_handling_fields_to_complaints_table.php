<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->text('handling_note')->nullable()->after('unit');
            $table->text('response_note')->nullable()->after('handling_note');
            $table->string('result_photo')->nullable()->after('response_note');
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['handling_note', 'response_note', 'result_photo']);
        });
    }
};
