<?php

namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Auth\RedirectByRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PortalLoginController extends Controller
{
    use RedirectByRole;

    public function showForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.portal-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'username.required' => 'Username tidak boleh kosong.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        $user = User::withTrashed()->where('username', $request->username)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Username tidak ditemukan.']);
        }

        if (!in_array($user->role, ['Admin', 'SuperAdmin'])) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Akun ini tidak memiliki akses ke portal admin.');
        }

        if ($user->deleted_at !== null) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi SuperAdmin.');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['password' => 'Password salah.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectByRole($user->role);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $referer = $request->headers->get('referer', '');
        if (str_contains($referer, 'admin') || str_contains($referer, 'superadmin')) {
            return redirect()->route('portal.login');
        }

        return redirect()->route('login');
    }
}