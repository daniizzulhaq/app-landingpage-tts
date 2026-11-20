<?php

namespace App\Http\Requests\Admin;

class RejectRequest extends SuperAdminRequest
{
    public function rules(): array
    {
        return ['catatan_revisi' => ['required', 'string', 'max:2000']];
    }

    public function attributes(): array
    {
        return ['catatan_revisi' => 'catatan revisi'];
    }
}
