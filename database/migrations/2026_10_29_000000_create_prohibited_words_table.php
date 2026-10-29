<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prohibited_words', function (Blueprint $table) {
            $table->id();
            $table->string('word', 50)->unique();
            $table->timestamps();
        });

        $now = now();
        $words = [
            'tai', 'anjing', 'babi', 'sialan', 'bajingan', 'cino', 'bodat',
            'monyet', 'cina', 'tahi', 'kafir', 'jancok', 'cok', 'goblok', 'asu', 'bego',
        ];

        DB::table('prohibited_words')->insert(array_map(
            fn (string $word) => ['word' => $word, 'created_at' => $now, 'updated_at' => $now],
            $words
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('prohibited_words');
    }
};
