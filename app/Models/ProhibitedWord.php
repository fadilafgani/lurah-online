<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProhibitedWord extends Model
{
    protected $fillable = ['word'];

    public static function matchingWord(array $texts): ?string
    {
        $words = static::query()->pluck('word');

        foreach ($texts as $text) {
            $tokens = static::normalizeToTokens((string) $text);

            foreach ($words as $word) {
                $normalizedWord = static::normalizeToTokens($word);

                if ($normalizedWord !== [] && in_array($normalizedWord[0], $tokens, true)) {
                    return $word;
                }
            }
        }

        return null;
    }

    private static function normalizeToTokens(string $text): array
    {
        $normalized = strtr(Str::lower($text), [
            '0' => 'o',
            '1' => 'i',
            '3' => 'e',
            '4' => 'a',
            '5' => 's',
            '7' => 't',
            '@' => 'a',
            '$' => 's',
        ]);

        return preg_split('/[^\pL\pN]+/u', $normalized, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    }
}
