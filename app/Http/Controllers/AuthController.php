<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function showForgot(): View
    {
        return view('auth.lupa-password');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        // Satu kolom "Email atau username": bentuk email -> cocokkan ke kolom email, selain itu ke username.
        $login = mb_strtolower(trim($data['login']));
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $login, 'password' => $data['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

           return redirect()->route('welcome.animation')
    ->with('success', 'Selamat datang kembali, '.Auth::user()->name.'!');
        }

        // Pesan sengaja tidak membedakan "akun tidak ada" dan "password salah" agar akun tidak bisa ditebak.
        return back()
            ->withErrors(['login' => 'Email/username atau password salah. Periksa kembali lalu coba lagi.'])
            ->onlyInput('login', 'remember');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->merge([
            'username' => mb_strtolower(trim((string) $request->input('username'))),
            'email' => mb_strtolower(trim((string) $request->input('email'))),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'username' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[a-z0-9_.-]+$/', 'unique:users,username'],
            'email' => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed', 'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'],
        ], [
            'username.regex' => 'Username hanya boleh berisi huruf kecil, angka, titik, strip, dan garis bawah.',
            'password.regex' => 'Password harus mengandung minimal satu huruf dan satu angka.',
        ]);

        // Password di-hash otomatis oleh cast 'hashed' pada model User. Peran selalu 'user'.
        $user = User::create([
            'name' => trim($data['name']),
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

       return redirect()->route('welcome.animation')
    ->with('success', 'Akun berhasil dibuat. Selamat datang di Maply, '.$user->name.'!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah keluar.');
    }
}
