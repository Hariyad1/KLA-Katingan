<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return response()->json(['data' => $users]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:user'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'status' => ['required', 'integer', 'in:0,1'],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => $request->status,
                'email_verified_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil ditambahkan',
                'data' => $user
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(User $user)
    {
        return response()->json(['data' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:user,email,'.$user->id],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $request->validate([
                'password' => [Rules\Password::defaults()],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui',
            'data' => $user
        ]);
    }

    public function destroy(User $user)
    {
        try {
            if (Auth::check() && Auth::user()->id === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus akun Anda sendiri'
                ], 403);
            }

            try {
                $user->tokens()->delete();
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error saat menghapus token: ' . $e->getMessage());
            }
            
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error saat menghapus pengguna: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pengguna: ' . $e->getMessage()
            ], 500);
        }
    }
} 