<?php

namespace App\Http\Requests\Admin;

class PageRequest extends SuperAdminRequest
{
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'isi' => ['nullable', 'string', 'max:100000'],
            'gambar' => ['nullable', ...self::IMAGE],
            'hapus_gambar' => ['nullable', 'boolean'],
        ];
    }
}
