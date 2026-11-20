<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/** Dasar Form Request untuk modul yang hanya boleh dikelola Super Admin. */
abstract class SuperAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isSuperAdmin();
    }

    protected const IMAGE = ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'];

    protected const FILE = ['file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:10240'];
}
