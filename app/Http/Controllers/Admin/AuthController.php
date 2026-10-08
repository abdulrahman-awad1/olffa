<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('admin.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // الـ role جزء من شروط الدخول: اليوزر العادي مابيتسجلش دخول أصلاً،
        // ورسالة الخطأ واحدة عشان ما نكشفش إن الباسورد صح لحساب مش أدمن.
        $attempt = [
            'email' => Str::lower($credentials['email']),
            'password' => $credentials['password'],
            'role' => User::ROLE_ADMIN,
        ];

        if (! Auth::attempt($attempt)) {
            throw ValidationException::withMessages(['email' => 'بيانات الدخول غير صحيحة']);
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
