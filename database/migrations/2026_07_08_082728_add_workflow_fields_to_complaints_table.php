<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            if (!Schema::hasColumn('complaints', 'unit')) {
                $table->string('unit')->nullable()->after('category');
            }
            if (!Schema::hasColumn('complaints', 'handling_note')) {
                $table->text('handling_note')->nullable()->after('status');
            }
            if (!Schema::hasColumn('complaints', 'response_note')) {
                $table->text('response_note')->nullable()->after('handling_note');
            }
            if (!Schema::hasColumn('complaints', 'result_photo')) {
                $table->string('result_photo')->nullable()->after('response_note');
            }
            if (!Schema::hasColumn('complaints', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('result_photo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            foreach (['unit', 'handling_note', 'response_note', 'result_photo', 'rejection_reason'] as $column) {
                if (Schema::hasColumn('complaints', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
