<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;
use Illuminate\Validation\Rule;

class ServiceRequest extends SuperAdminRequest
{
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'ikon' => ['required', Rule::in(array_keys(Service::ICONS))],
            'unit_id' => ['nullable', 'exists:units,id'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'persyaratan' => ['nullable', 'string', 'max:10000'],
            'prosedur' => ['nullable', 'string', 'max:10000'],
            'biaya' => ['nullable', 'string', 'max:2000'],
            'jadwal' => ['nullable', 'string', 'max:2000'],
            'lokasi' => ['nullable', 'string', 'max:2000'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'files' => ['nullable', 'array', 'max:10'],
            'files.*' => self::FILE,
            'file_names' => ['nullable', 'array'],
            'file_names.*' => ['nullable', 'string', 'max:150'],
            'hapus_files' => ['nullable', 'array'],
            'hapus_files.*' => ['integer'],
        ];
    }

    public function attributes(): array
    {
        return ['files.*' => 'formulir'];
    }
}
