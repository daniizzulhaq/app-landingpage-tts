<?php

namespace App\Http\Requests\Admin;

class FaqRequest extends SuperAdminRequest
{
    public function rules(): array
    {
        return [
            'pertanyaan' => ['required', 'string', 'max:500'],
            'jawaban' => ['required', 'string', 'max:20000'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }
}
