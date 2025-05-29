<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;

class AkunController extends Controller 
{
    public function pengaturan()
    {
        // Ensure Auth::user() returns an instance of App\Models\User
        $user = Auth::user();
        if (!($user instanceof \App\Models\User)) {
            $user = \App\Models\User::find(Auth::id());
        }
        return view('akun.pengaturan', compact('user'));
    }

public function update(Request $request)
{
    $user = Auth::user();


    if (!$user) {
        return redirect()->route('login')->withErrors(['msg' => 'Silahkan login terlebih dahulu']);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
        'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        'password' => 'nullable|string|min:8|confirmed',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);


    // Pastikan $user adalah instance dari User
    if ($user instanceof \App\Models\User) {
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;


        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Hapus foto jika user centang "hapus_foto"
        if ($request->has('hapus_foto') && $user->foto) {
        if (file_exists(public_path('foto/' . $user->foto))) {
        unlink(public_path('foto/' . $user->foto));
        }
        $user->foto = null; // kembali ke default
        }


        if ($request->hasFile('foto')) {

            if ($user->foto && file_exists(public_path('fot/' . $user->foto))) {
                unlink(public_path('foto/' . $user->foto));
            }

            $foto = $request->file('foto');
            $namaFoto = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('foto'), $namaFoto);
            $user->foto = $namaFoto;
        }

        $user->save(); // Pastikan ini dipanggil pada instance User yang valid

        Auth::user()->name = $user->name;
        Auth::user()->username = $user->username;
        Auth::user()->email = $user->email;
        Auth::user()->foto = $user->foto ?? Auth::user()->foto;
        Auth::login($user);

        return redirect()->back()->with('success', 'Data akun berhasil diperbaharui');
    } else {
        return redirect()->back()->withErrors(['msg' => 'Pengguna tidak ditemukan']);
    }
}
}