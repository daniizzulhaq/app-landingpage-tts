<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class InformationRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'kontak' => ['required', 'string', 'max:150'],
            'informasi_diminta' => ['required', 'string', 'min:10', 'max:3000'],
            'tujuan' => ['required', 'string', 'min:5', 'max:2000'],
            'website' => ['nullable', 'string', 'max:255'], // honeypot
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama lengkap',
            'kontak' => 'nomor HP / email',
            'informasi_diminta' => 'informasi yang diminta',
            'tujuan' => 'tujuan penggunaan informasi',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return route('site.ppid').'#permohonan';
    }
}
