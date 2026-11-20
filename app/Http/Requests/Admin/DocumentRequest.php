<?php

namespace App\Http\Requests\Admin;

use App\Models\Document;
use Illuminate\Validation\Rule;

class DocumentRequest extends SuperAdminRequest
{
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'kategori' => ['required', Rule::in(array_keys(Document::CATEGORIES))],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'tahun' => ['nullable', 'integer', 'min:1990', 'max:'.(now()->year + 1)],
            'file' => [$this->route('document') ? 'nullable' : 'required', ...self::FILE],
        ];
    }

    public function attributes(): array
    {
        return ['file' => 'berkas dokumen'];
    }
}
