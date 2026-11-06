<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $users = User::all();
        return view('auth.login', compact('users'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil!',
                    'redirect' => route('dashboard')
                ]);
            }
            return redirect()->intended(route('dashboard'));
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat email atau kata sandi tidak sesuai dengan pangkalan data SIAKAD UTM.'
            ], 422);
        }

        return back()->withErrors([
            'email' => 'Alamat email atau kata sandi tidak valid.',
        ])->withInput($request->only('email'));
    }

    public function quickLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();
        Auth::login($user);
        $request->session()->regenerate();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Login sebagai ' . $user->name,
                'redirect' => route('dashboard')
            ]);
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('login')
            ]);
        }

        return redirect()->route('login');
    }
}

