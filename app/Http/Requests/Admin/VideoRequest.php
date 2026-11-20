<?php

namespace App\Http\Requests\Admin;

class VideoRequest extends SuperAdminRequest
{
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'youtube_url' => ['required', 'url', 'max:500', function ($attr, $value, $fail) {
                if (! youtube_id($value)) {
                    $fail('Tautan harus berupa URL video YouTube yang valid.');
                }
            }],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'tanggal' => ['nullable', 'date'],
        ];
    }
}
