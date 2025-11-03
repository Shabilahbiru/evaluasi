<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin_master')->get();
        return view('admin.kelola-role', compact('users'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:admin,reviewer',
        ]);

        $user = User::find($request->user_id);
        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', 'Role berhasil diperbaharui.');
    }
}
