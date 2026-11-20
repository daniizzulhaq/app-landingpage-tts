<?php

namespace App\Http\Requests\Admin;

use App\Models\InformationRequest;
use Illuminate\Validation\Rule;

class InformationRequestUpdateRequest extends SuperAdminRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(array_keys(InformationRequest::STATUSES))],
            'catatan' => ['nullable', 'string', 'max:3000'],
        ];
    }
}
