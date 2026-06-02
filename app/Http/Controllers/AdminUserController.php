<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Tampilkan semua daftar pengguna (Read)
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('login', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.users', compact('users'));
    }

    /**
     * Buat Pengguna Baru (Create)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:users',
            'email' => 'nullable|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        User::create([
            'name' => $request->name,
            'login' => $request->login,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back()->with('success', 'Akun ' . $request->name . ' berhasil didaftarkan sebagai ' . strtoupper($request->role) . '.');
    }

    /**
     * Edit Pengguna (Update)
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'login' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => 'nullable|string|min:6',
        ]);

        // Kunci Keamanan: Admin dilarang menurunkan jabatannya sendiri!
        if ($user->id === Auth::id() && $request->role !== 'admin') {
            return back()->with('error', 'Sistem Otoritas: Anda tidak dapat mengubah jabatan akun Anda sendiri menjadi user biasa.');
        }

        $data = [
            'name' => $request->name,
            'login' => $request->login,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Hanya perbarui password jika admin mengisinya
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Data profil ' . $user->name . ' berhasil diperbarui.');
    }

    /**
     * Hapus akun pengguna (Delete)
     */
    public function destroy(User $user)
    {
        // Kunci Keamanan: Admin dilarang menghapus akunnya sendiri!
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Sistem Keamanan Terpicu: Anda tidak dapat menghapus akun Anda sendiri (Self-Destruct dilarang).');
        }

        $user->delete();

        return back()->with('success', 'Akun berhasil dihapus secara permanen dari sistem.');
    }
}
