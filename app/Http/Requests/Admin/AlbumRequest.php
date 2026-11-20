<?php

namespace App\Http\Requests\Admin;

use App\Models\Album;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlbumRequest extends FormRequest
{
    public function authorize(): bool
    {
        $album = $this->route('album');

        return $album ? $this->user()->can('update', $album) : $this->user()->can('create', Album::class);
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'tanggal' => ['nullable', 'date'],
            'unit_id' => [$this->user()->isSuperAdmin() ? 'nullable' : 'prohibited', 'exists:units,id'],
            'photos' => [$this->route('album') ? 'nullable' : 'required', 'array', 'max:50'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'captions' => ['nullable', 'array'],
            'captions.*' => ['nullable', 'string', 'max:255'],
            'action' => ['required', Rule::in(['draft', 'submit'])],
        ];
    }

    public function attributes(): array
    {
        return ['photos' => 'foto', 'photos.*' => 'foto'];
    }
}
