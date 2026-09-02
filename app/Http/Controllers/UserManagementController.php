<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserManagementController extends Controller
{
    private function userSummary(User $user): array
    {
        return [
            'id'                   => $user->id,
            'name'                 => $user->name,
            'email'                => $user->email,
            'contact_number'       => $user->contact_number,
            'address'              => $user->address,
            'position'             => $user->position,
            'status'               => $user->status,
            'must_change_password' => $user->must_change_password,
            'roles'                => $user->getRoleNames()->toArray(),
            'created_at'           => $user->created_at,
        ];
    }

    public function index()
    {
        $activeUsers = User::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(fn (User $user) => $this->userSummary($user));

        $inactiveUsers = User::where('status', 'inactive')
            ->orderBy('name')
            ->get()
            ->map(fn (User $user) => $this->userSummary($user));

        return view('admin.users', compact('activeUsers', 'inactiveUsers'));
    }

    private function userValidationRules(?User $user = null): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', 'unique:users,email,' . ($user?->id ?? 'NULL')],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'address'        => ['nullable', 'string', 'max:255'],
            'position'       => ['nullable', 'string', 'max:100'],
            'role'           => ['required', 'in:officer,farmer'],
            'status'         => ['sometimes', 'required', 'in:active,inactive'],
        ];
    }

    public function addUser(Request $request)
    {
        $validated = $request->validate(array_merge([
            'password' => ['required', 'string', 'min:8'],
        ], $this->userValidationRules()));

        $user = User::create([
            'name'                 => $validated['name'],
            'email'                => $validated['email'],
            'password'             => $validated['password'],
            'contact_number'       => $validated['contact_number'] ?? null,
            'address'              => $validated['address'] ?? null,
            'position'             => $validated['position'] ?? null,
            'status'               => 'active',
            'must_change_password' => true,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->back()->with(
            'success',
            'User account created successfully. They\'ll be asked to set a new password on first login.'
        );
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Admin accounts cannot be edited from User Management.');
        }

        $validated = $request->validate($this->userValidationRules($user));

        $user->update([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'contact_number' => $validated['contact_number'] ?? null,
            'address'        => $validated['address'] ?? null,
            'position'       => $validated['position'] ?? null,
            'status'         => $validated['status'] ?? $user->status,
        ]);

        if (! $user->hasRole($validated['role'])) {
            $user->syncRoles([$validated['role']]);
        }

        return redirect()->back()->with('success', 'User account updated successfully.');
    }

    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Admin accounts cannot be deactivated from User Management.');
        }

        $user->status = 'inactive';
        $user->save();

        return redirect()->back()->with('success', 'User account deactivated successfully.');
    }

    public function reactivateUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Admin accounts cannot be reactivated from User Management.');
        }

        $user->status = 'active';
        $user->save();

        return redirect()->back()->with('success', 'User account reactivated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Admin accounts cannot be deleted from User Management.');
        }

        if ($user->status === 'active') {
            return redirect()->back()->with('error', 'Only deactivated users can be permanently deleted.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User account permanently deleted successfully.');
    }

    public function exportCsv()
    {
        $users = User::orderBy('status')->orderBy('name')->get();

        $fileName = 'psareco-user-list-' . now()->format('Y-m-d-His') . '.csv';
        $handle = fopen('php://temp', 'r+');

        $header = [
            'ID',
            'Name',
            'Email',
            'Role',
            'Status',
            'Contact Number',
            'Address',
            'Position',
            'Created At',
            'Must Change Password',
        ];

        fputcsv($handle, $header);

        foreach ($users as $user) {
            fputcsv($handle, [
                $user->id,
                $user->name,
                $user->email,
                $user->getRoleNames()->first() ?? 'N/A',
                ucfirst($user->status),
                $user->contact_number ?? '',
                $user->address ?? '',
                $user->position ?? '',
                $user->created_at?->format('Y-m-d H:i:s') ?? '',
                $user->must_change_password ? 'Yes' : 'No',
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
