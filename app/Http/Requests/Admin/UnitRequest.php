<?php

namespace App\Http\Requests\Admin;

use App\Models\Unit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        $unit = $this->route('unit');

        return $unit ? $this->user()->can('update', $unit) : $this->user()->can('create', Unit::class);
    }

    public function rules(): array
    {
        $super = $this->user()->isSuperAdmin();

        return [
            // Admin Unit tidak boleh mengubah jenis, nama, dan urutan unit.
            'type' => [$super ? 'required' : 'prohibited', Rule::in(array_keys(Unit::TYPES))],
            'name' => [$super ? 'required' : 'prohibited', 'string', 'max:150'],
            'urutan' => [$super ? 'nullable' : 'prohibited', 'integer', 'min:0', 'max:9999'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'tugas_pokok' => ['nullable', 'string', 'max:30000'],
            'layanan_unit' => ['nullable', 'string', 'max:30000'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'wilayah_pelayanan' => ['nullable', 'string', 'max:2000'],
            'maps_url' => ['nullable', 'url', 'max:1000'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'hapus_foto' => ['nullable', 'boolean'],
        ];
    }
}
