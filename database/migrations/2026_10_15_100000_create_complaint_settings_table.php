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
        Schema::create('complaint_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_daily_limit_active')->default(true);
            $table->unsignedInteger('max_daily_complaints')->default(2);
            $table->foreignId('last_updated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Seed baris konfigurasi default awal
        \DB::table('complaint_settings')->insert([
            'is_daily_limit_active' => true,
            'max_daily_complaints' => 2,
            'last_updated_by_user_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_settings');
    }
};
