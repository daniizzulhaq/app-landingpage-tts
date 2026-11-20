<?php

namespace App\Http\Requests\Admin;

use App\Models\Official;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfficialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'unit_id' => ['nullable', 'exists:units,id'],
            'nama' => ['required', 'string', 'max:150'],
            'pangkat' => ['nullable', 'string', 'max:100'],
            'jabatan' => ['required', 'string', 'max:150'],
            'level' => ['required', Rule::in(array_keys(Official::LEVELS))],
            'tugas' => ['nullable', 'string', 'max:30000'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'hapus_foto' => ['nullable', 'boolean'],
        ];
    }
}
