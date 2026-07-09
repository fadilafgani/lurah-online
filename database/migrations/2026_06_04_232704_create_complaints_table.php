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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();

            $table->string('ticket_code')->unique();

            $table->string('name')->nullable();

            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->string('category');

            $table->string('photo')->nullable();

            $table->enum('status', [
                'submitted',
                'verified',
                'process',
                'completed',
                'rejected'
            ])->default('submitted');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};