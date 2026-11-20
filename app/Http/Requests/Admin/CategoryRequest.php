<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class CategoryRequest extends SuperAdminRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($this->route('category')?->id)],
        ];
    }
}
