<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->foreignId('resident_id')
                ->nullable()
                ->after('ticket_code')
                ->constrained('residents')
                ->nullOnDelete();
            $table->boolean('is_anonymous')->default(false)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign(['resident_id']);
            $table->dropColumn(['resident_id', 'is_anonymous']);
        });
    }
};
