<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the admin accounts (PAGE-ADM-006, ADM-FR-009).
     */
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        return view('admin.users.index', [
            'users' => User::query()->with('role')->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new admin account.
     */
    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('admin.users.form', [
            'user' => new User(['is_active' => true]),
            'roles' => $this->roles(),
        ]);
    }

    /**
     * Store a newly created admin account.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'role_id' => ['required', 'integer', Rule::exists('roles', 'id')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        User::query()->create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Akun admin berhasil dibuat.');
    }

    /**
     * Show the form for editing the given admin account.
     */
    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('admin.users.form', [
            'user' => $user,
            'roles' => $this->roles(),
        ]);
    }

    /**
     * Update the given admin account.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')->ignore($user->id)],
            'role_id' => ['required', 'integer', Rule::exists('roles', 'id')],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($user->isOwner() && (int) $data['role_id'] !== (int) $user->role_id) {
            return back()->withErrors([
                'role_id' => 'Role akun Owner tidak dapat diubah.',
            ])->withInput();
        }

        $data['is_active'] = $request->boolean('is_active');

        if ($user->id === $request->user()?->id && ! $data['is_active']) {
            return back()->withErrors([
                'is_active' => 'Anda tidak dapat menonaktifkan akun yang sedang Anda pakai.',
            ])->withInput();
        }

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Akun admin berhasil diperbarui.');
    }

    /**
     * Remove the given admin account.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Akun admin berhasil dihapus.');
    }

    /**
     * Get the roles that can be assigned from the dashboard.
     *
     * @return Collection<int, Role>
     */
    private function roles()
    {
        return Role::query()->whereIn('name', [Role::ADMIN, Role::OWNER])->orderBy('label')->get();
    }
}
