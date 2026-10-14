<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman form login admin/backend.
     */
    public function index(): View
    {
        return view('backend.v_login.login');
    }

    /**
     * Menampilkan halaman form lupa password.
     */
    public function lupaPassword(): View
    {
        return view('backend.v_login.lupa_password');
    }

    /**
     * Memproses verifikasi kredensial login.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        // 1. Validasi input dari form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Tambahkan kondisi bahwa user harus aktif (status = 1)
        $credentialsWithStatus = array_merge($credentials, ['status' => 1]);

        // 3. Autentikasi kredensial pengguna
        if (Auth::attempt($credentialsWithStatus)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Direct halaman berdasarkan role
            if ($user->role == '2' || $user->role == 'ajudan') {
                // Ajudan langsung diarahkan ke halaman Layanan Surat
                return redirect()->route('backend.sambutan.index');
            }

            // Super Admin & Admin diarahkan ke Beranda/Dashboard
            return redirect()->route('backend.v_beranda.index');
        }

        // 4. Jika autentikasi gagal
        return back()
            ->withInput($request->only('email'))
            ->with('loginError', 'Email/password salah atau akun Anda belum aktif.');
    }

    /**
     * Memproses pemutusan sesi (logout).
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Memproses reset password baru
     */
    public function prosesLupaPassword(Request $request): RedirectResponse
    {
        // 1. Validasi input dari form reset password
        $request->validate([
            'email' => ['required', 'email', 'exists:user,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // 2. Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar dalam sistem.'])->withInput();
        }

        // 3. Update password user dengan hash
        $user->password = Hash::make($request->password);
        $user->save();

        // 4. Kembali ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Password berhasil diperbarui! Silakan login.');
    }
}