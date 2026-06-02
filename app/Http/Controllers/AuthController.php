<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     * Login bisa pakai nama lengkap ATAU nomor telepon.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $request->input('login');

        // Cari user berdasarkan kolom 'login' (no. telp) atau 'name' (nama lengkap)
        $user = User::where('login', $loginInput)
                     ->orWhere('name', $loginInput)
                     ->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            Auth::login($user, $request->boolean('remember-me'));

            $request->session()->regenerate();

            return redirect()->intended('/')->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        return back()->withErrors([
            'login' => 'Nama/No. Telepon atau Password salah.',
        ])->withInput($request->only('login'));
    }

    /**
     * Tampilkan halaman register.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses register user baru.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'login' => [
                'required',
                'string',
                'max:255',
                'unique:users,login',
                function ($attribute, $value, $fail) {
                    // Jika input berupa angka/nomor telepon, wajib diawali dengan '08'
                    if (preg_match('/^[0-9+]+$/', $value)) {
                        if (!str_starts_with($value, '08')) {
                            $fail('Format nomor telepon harus diawali dengan 08 (bukan 62 atau +62).');
                        }
                    }
                }
            ],
            'password' => 'required|string|min:6',
        ], [
            'login.unique' => 'Username atau Nomor Telepon sudah digunakan.',
        ]);

        // Generate PIN spesial 5-digit secara otomatis
        $pin = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'pin' => $pin,
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Akun berhasil dibuat! PIN Spesial Anda untuk pemulihan: ' . $pin);
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
