<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('login');
    }

    public function loginAction(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $user = User::where('username', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Simpan data ke session
            session(['user_id' => $user->id, 'role' => $user->role]);
            session(['username' => $user->username]);
            session(['name' => $user->name]);
            return redirect('/dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors(['username' => 'Username atau password salah.']);
    }


    public function forgotForm()
{
    return view('forgot');
}

public function forgotAction(Request $request)
{

    $request->validate([
       'email' => 'required|email|exists:users,email', 
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email tidak ditemukan']);
    }

    $token = Str::random(64);
    $user->reset_token = $token;
    $user->save();

    Mail::send('emails.reset-password', ['user' => $user, 'token' => $token], function ($message) use ($user) {
    $message->to($user->email);
    $message->subject('Reset Password Anda');
    });

    return redirect()->back()->with('success', 'Link reset password telah dikirim ke email anda.');
}

public function resetForm($token)
{
    return view('reset', compact('token'));
}

public function resetAction(Request $request)
{
    $request->validate([
        'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
        ], [
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.'
    ]);

    $user = User::where('reset_token', $request->token)->first();

    if (!$user) {
        return back()->withErrors(['token' => 'Token tidak valid']);
    }

    $user->password = Hash::make($request->password);
    $user->reset_token = null;
    $user->save();

    return redirect('/login')->with('success', 'Password berhasil direset, silakan login.');
}


    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
    public function registerForm()
    {
        return view('register');
    }

    public function registerAction(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'username' => 'required|string|max:100|unique:users',
            'email' => 'required|email|max:150|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
        ], [
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'reviewer', // default
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}

