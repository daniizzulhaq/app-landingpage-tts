<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordRequest;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('admin.auth.password');
    }

    public function update(PasswordRequest $request)
    {
        $request->user()->update(['password' => $request->validated('password')]);

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }
}
