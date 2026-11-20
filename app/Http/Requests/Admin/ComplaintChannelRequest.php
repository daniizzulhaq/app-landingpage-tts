<?php

namespace App\Http\Requests\Admin;

use App\Models\ComplaintChannel;
use Illuminate\Validation\Rule;

class ComplaintChannelRequest extends SuperAdminRequest
{
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'url' => ['nullable', 'string', 'max:500', 'regex:#^(https?://|tel:|mailto:)#'],
            'ikon' => ['required', Rule::in(array_keys(ComplaintChannel::ICONS))],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    public function messages(): array
    {
        return ['url.regex' => 'Tautan harus diawali https://, tel:, atau mailto:.'];
    }
}
