<?php

namespace App\Http\Requests\Admin;

class SliderRequest extends SuperAdminRequest
{
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'subjudul' => ['nullable', 'string', 'max:500'],
            'link' => ['nullable', 'string', 'max:500', 'regex:#^(https?://|/)#'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'gambar' => [$this->route('slider') ? 'nullable' : 'required', ...self::IMAGE],
        ];
    }

    public function messages(): array
    {
        return ['link.regex' => 'Tautan harus diawali http://, https://, atau / (tautan internal).'];
    }
}
