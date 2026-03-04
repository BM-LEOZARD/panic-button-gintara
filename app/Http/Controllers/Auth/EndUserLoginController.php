<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class EndUserLoginController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required'    => 'Email tidak boleh kosong.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        $user = User::withTrashed()->where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if ($user->deleted_at !== null) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi customer service.');
        }

        if ($user->role !== 'EndUser') {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Akun ini bukan akun pelanggan. Gunakan Portal Login untuk Admin.');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'Password salah.']);
        }

        if ($user->hasVerifiedOtp()) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        $this->sendOtp($user);

        $request->session()->put('otp_user_id', $user->id);
        $request->session()->put('otp_remember', $request->boolean('remember'));

        return redirect()->route('otp.show')
            ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showOtp(Request $request)
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('login')
                ->with('error', 'Sesi login tidak valid. Silakan login kembali.');
        }

        $user = User::find($request->session()->get('otp_user_id'));

        if (!$user) {
            $request->session()->forget(['otp_user_id', 'otp_remember']);
            return redirect()->route('login')->with('error', 'Sesi tidak valid.');
        }

        return view('auth.otp', [
            'maskedEmail' => $this->maskEmail($user->email),
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6|regex:/^[0-9]{6}$/',
        ], [
            'otp.required' => 'Kode OTP tidak boleh kosong.',
            'otp.size'     => 'Kode OTP harus 6 digit.',
            'otp.regex'    => 'Kode OTP hanya boleh berisi angka.',
        ]);

        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('login')
                ->with('error', 'Sesi login tidak valid. Silakan login kembali.');
        }

        $user = User::find($request->session()->get('otp_user_id'));

        if (!$user) {
            $request->session()->forget(['otp_user_id', 'otp_remember']);
            return redirect()->route('login')->with('error', 'Sesi tidak valid.');
        }

        $otpRecord = OtpCode::where('email', $user->email)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        $otpRecord->update(['used' => true]);

        $user->update(['otp_verified_at' => now()]);

        $remember = $request->session()->get('otp_remember', false);
        $request->session()->forget(['otp_user_id', 'otp_remember']);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Verifikasi berhasil! Selamat datang, ' . $user->name . '.');
    }

    public function resendOtp(Request $request)
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('login')
                ->with('error', 'Sesi login tidak valid. Silakan login kembali.');
        }

        $user = User::find($request->session()->get('otp_user_id'));

        if (!$user) {
            $request->session()->forget(['otp_user_id', 'otp_remember']);
            return redirect()->route('login')->with('error', 'Sesi tidak valid.');
        }

        $this->sendOtp($user);

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    private function sendOtp(User $user): void
    {
        OtpCode::where('email', $user->email)
            ->where('used', false)
            ->update(['used' => true]);

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'email'      => $user->email,
            'otp'       => $code,
            'expired_at' => now()->addMinutes(3),
            'used'       => false,
        ]);

        Mail::to($user->email)->send(new OtpMail($code, $user->name));
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email);
        $maskedLocal = substr($local, 0, 1) . str_repeat('*', max(strlen($local) - 2, 1)) . substr($local, -1);
        return $maskedLocal . '@' . $domain;
    }

    private function redirectByRole(string $role)
    {
        return match ($role) {
            'EndUser'    => redirect()->route('dashboard'),
            'Admin'      => redirect()->route('admin.dashboard'),
            'SuperAdmin' => redirect()->route('superadmin.dashboard'),
            default      => redirect()->route('login'),
        };
    }
}
