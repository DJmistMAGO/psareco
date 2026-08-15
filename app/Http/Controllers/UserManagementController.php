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
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
                'created_at' => $user->created_at,
                'status' => $user->status == 'active' ? 'Active' : '',
            ];
        });

        $pendingUsers = User::where('status', 'pending')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
                'created_at' => $user->created_at,
                'status' => $user->status == 'pending' ? 'Pending' : '',
            ];
        });

        $inactiveUsers = User::where('status', 'inactive')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
                'created_at' => $user->created_at,
                'status' => $user->status == 'inactive' ? 'Inactive' : '',
            ];
        });

        return view('admin.users', compact('activeUsers', 'pendingUsers', 'inactiveUsers'));
    }

    public function addUser(Request $request)
    {
        $validated = $request->validate([
            'name' =>'required|string|max:255',
            'email' => 'required|email|unique:users,email,max:255',
            'password' => 'required|string',
            'role' => 'required|in:officer,farmer',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'status' => 'pending',
        ]);
        $user->assignRole($validated['role']);

        return redirect()->back()->with('success', 'User account created successfully.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id . '|max:255',
            'role' => 'required|in:officer,farmer',
            'status' => 'required|in:active,pending,inactive',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => $validated['status'],
        ]);

        if ($user->hasRole($validated['role'])) {
        } else {
            $user->syncRoles([$validated['role']]);
        }

        return redirect()->back()->with('success', 'User account updated successfully.');
    }

    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);
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

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        return redirect()->back()->with('success', 'User account approved successfully.');
    }

    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'inactive';
        $user->save();

        return redirect()->back()->with('success', 'User account rejected successfully.');
    }


}
