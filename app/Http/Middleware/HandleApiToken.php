<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;

class HandleApiToken
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !session()->has('api_token')) {
            // Dapatkan ID user dari Auth
            $userId = Auth::id();
            
            // Ambil model User langsung dari database
            $user = User::find($userId);
            
            if ($user) {
                $existingToken = $user->tokens()->latest()->first();
                
                if ($existingToken) {
                    // Gunakan token yang sudah ada
                    session(['api_token' => $existingToken->plain_text_token ?? $existingToken->token]);
                } else {
                    // Buat token baru jika tidak ada
                    $token = $user->createToken('api_token')->plainTextToken;
                    session(['api_token' => $token]);
                }
            }
        }

        return $next($request);
    }
} 