<?php

namespace App\Http\Requests\Admin;

use App\Models\Agenda;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $agenda = $this->route('agenda');

        return $agenda ? $this->user()->can('update', $agenda) : $this->user()->can('create', Agenda::class);
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string', 'max:3000'],
            'lokasi' => ['nullable', 'string', 'max:200'],
            'mulai' => ['required', 'date'],
            'selesai' => ['nullable', 'date', 'after_or_equal:mulai'],
            'unit_id' => [$this->user()->isSuperAdmin() ? 'nullable' : 'prohibited', 'exists:units,id'],
            'action' => ['required', Rule::in(['draft', 'submit'])],
        ];
    }
}
