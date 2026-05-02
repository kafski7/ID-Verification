<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserStoreRequest;
use App\Http\Requests\AdminUserUpdateRequest;
use App\Models\AdminUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $users = AdminUser::orderBy('name')->paginate(20);

        return view('admin.admin-users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.admin-users.create');
    }

    public function store(AdminUserStoreRequest $request): RedirectResponse
    {
        AdminUser::create($request->validated());

        return redirect()->route('admin.admin-users.index')
            ->with('success', 'Admin user created successfully.');
    }

    public function edit(AdminUser $adminUser): View
    {
        return view('admin.admin-users.edit', compact('adminUser'));
    }

    public function update(AdminUserUpdateRequest $request, AdminUser $adminUser): RedirectResponse
    {
        $data = $request->validated();

        // Only update password when explicitly provided
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $adminUser->update($data);

        return redirect()->route('admin.admin-users.index')
            ->with('success', 'Admin user updated successfully.');
    }

    public function deactivate(AdminUser $adminUser): RedirectResponse
    {
        // Prevent SUPER_ADMIN from deactivating their own account
        if ($adminUser->is(auth()->user())) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        // Deactivate by clearing password (blocks login) and flagging role
        $adminUser->update(['role' => 'INACTIVE']);

        return redirect()->route('admin.admin-users.index')
            ->with('success', 'Admin user deactivated.');
    }
}
