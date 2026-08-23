<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {

        $activeUsers = User::where('status', 'active')->get()->map(function ($user) {
            return [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'roles'      => $user->getRoleNames()->toArray(),
                'created_at' => $user->created_at,
                'status'     => 'Active',
            ];
        });

        $inactiveUsers = User::where('status', 'inactive')->get()->map(function ($user) {
            return [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'roles'      => $user->getRoleNames()->toArray(),
                'created_at' => $user->created_at,
                'status'     => 'Inactive',
            ];
        });

        return view('admin.users', compact('activeUsers', 'inactiveUsers'));
    }

    public function addUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', 'in:officer,farmer'],
        ]);

        $user = User::create([
            'name'                 => $validated['name'],
            'email'                => $validated['email'],
            'password'             => bcrypt($validated['password']),
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

        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role'   => ['required', 'in:officer,farmer'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $user->update([
            'name'   => $validated['name'],
            'email'  => $validated['email'],
            'status' => $validated['status'],
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
        $user->status = 'active';
        $user->save();

        return redirect()->back()->with('success', 'User account reactivated successfully.');
    }

    // public function approveUser($id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->status = 'active';
    //     $user->save();

    //     return redirect()->back()->with('success', 'User account approved successfully.');
    // }

    // public function rejectUser($id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->status = 'inactive';
    //     $user->save();

    //     return redirect()->back()->with('success', 'User account rejected successfully.');
    // }


}
