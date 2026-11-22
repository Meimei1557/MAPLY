<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(
        Request $request
    ): RedirectResponse {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('login')
                ->with('error', 'Login Google gagal. Silakan coba kembali.');
        }

        // Hanya menerima email Google yang sudah terverifikasi.
        if (!($googleUser->user['verified_email'] ?? false)) {
            return redirect()->route('login')
                ->with('error', 'Email Google belum terverifikasi.');
        }

        $email = strtolower(trim($googleUser->getEmail() ?? ''));

        if (!$email) {
            return redirect()->route('login')
                ->with('error', 'Google tidak memberikan alamat email.');
        }

        // Cari akun yang sebelumnya sudah terhubung dengan Google.
        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            // Periksa apakah email sudah terdaftar di Maply.
            $user = User::where('email', $email)->first();

            if ($user) {
                // Hubungkan akun lama berdasarkan email Google terverifikasi.
                $user->forceFill([
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ])->save();
            } else {
                // Buat username otomatis yang unik.
                $base = Str::slug(
                    Str::before($email, '@'),
                    '_'
                );

                $base = Str::limit($base ?: 'googleuser', 24, '');

                $username = $base;
                $number = 1;

                while (User::where('username', $username)->exists()) {
                    $username = $base . $number;
                    $number++;
                }

                // Buat akun baru dengan role default user.
                $user = User::create([
                    'name' => $googleUser->getName() ?: 'Pengguna Google',
                    'username' => $username,
                    'email' => $email,
                    'password' => Str::random(40),
                ]);

                $user->forceFill([
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(),
                ])->save();
            }
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('welcome.animation')
            ->with('success', 'Berhasil masuk ke Maply menggunakan Google!');
    }
}
