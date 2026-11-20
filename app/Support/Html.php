<?php

namespace App\Support;

use Mews\Purifier\Facades\Purifier;

/**
 * Membersihkan HTML dari editor teks sebelum disimpan (mencegah XSS).
 */
class Html
{
    public static function clean(?string $html): ?string
    {
        if ($html === null || trim(strip_tags($html, '<img><iframe>')) === '') {
            return null;
        }

        return Purifier::clean($html, 'editor');
    }

    /** Bersihkan beberapa field sekaligus pada array data. */
    public static function cleanFields(array $data, array $fields): array
    {
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = self::clean($data[$field]);
            }
        }

        return $data;
    }
}
