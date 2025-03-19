<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    // TODO: Display the login view.
    public function create(): View
    {
        return view('auth.login');
    }

    // TODO: Handle an incoming authentication request.
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();
        // Hapus token lama jika ada
        $user->tokens()->delete();
        // Buat token baru hanya saat login
        $token = $user->createToken('api_token')->plainTextToken;
        session(['api_token' => $token]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    // TODO: Destroy an authenticated session.
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->user()) {
            // Hapus token saat logout
            $request->user()->tokens()->delete();
            session()->forget('api_token');
        }
        
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
