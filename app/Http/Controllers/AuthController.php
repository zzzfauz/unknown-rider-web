<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CommunityMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'member',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password tidak sesuai.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user());
    }

    // Mengarahkan pengguna berdasarkan role dan keanggotaan
    private function redirectByRole(User $user)
    {
        // Admin langsung menuju Dashboard Admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Periksa keanggotaan aktif
        $activeMembership = CommunityMember::where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();

        if ($activeMembership) {
            return redirect()->route('dashboard');
        }

        // Periksa pengajuan yang masih menunggu persetujuan
        $pendingMembership = CommunityMember::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($pendingMembership) {
            return redirect()
                ->route('communities.index')
                ->with('info', 'Pengajuan bergabung kamu sedang menunggu persetujuan Ketua.');
        }

        // Pengguna yang belum bergabung diarahkan ke daftar komunitas
        return redirect()->route('communities.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}