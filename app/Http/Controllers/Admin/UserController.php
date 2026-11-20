<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with(['unit', 'roles'])
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', '%'.$request->q.'%')->orWhere('email', 'like', '%'.$request->q.'%')))
            ->when($request->filled('role'), fn ($q) => $q->role($request->role))
            ->when($request->filled('unit_id'), fn ($q) => $q->where('unit_id', $request->unit_id))
            ->orderBy('name')
            ->paginate(20)->withQueryString();

        return view('admin.users.index', ['users' => $users, 'units' => Unit::ordered()->get()]);
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User(['is_active' => true]), 'units' => Unit::ordered()->get(), 'role' => User::ADMIN_UNIT]);
    }

    public function store(UserRequest $request)
    {
        $data = $this->payload($request);
        $user = User::create($data);
        $user->syncRoles([$request->validated('role')]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', [
            'user' => $user,
            'units' => Unit::ordered()->get(),
            'role' => $user->getRoleNames()->first() ?? User::ADMIN_UNIT,
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $this->payload($request);
        if ($user->is($request->user())) {
            // Cegah Super Admin mengunci dirinya sendiri.
            $data['is_active'] = true;
            $data['unit_id'] = null;
            $role = User::SUPER_ADMIN;
        } else {
            $role = $request->validated('role');
        }
        if (empty($data['password'])) {
            unset($data['password']);
        }
        $user->update($data);
        $user->syncRoles([$role]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }
        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    private function payload(UserRequest $request): array
    {
        $data = $request->safe()->only(['name', 'email', 'unit_id', 'password']);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->validated('role') === User::SUPER_ADMIN) {
            $data['unit_id'] = null;
        }

        return $data;
    }
}
