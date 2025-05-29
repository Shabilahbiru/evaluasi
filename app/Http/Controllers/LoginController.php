<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            session([
                'username' => $user->username,
                'role' => $user->role ?? 'admin'
            ]);
            return redirect()->intended('/dashboard')->with('success', 'Berhasil login!');

        }

        return back()->withErrors(['login' => 'Username atau password salah'])->withInput();

    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/home')->with('success', 'Berhasil logout!');
    }
}
