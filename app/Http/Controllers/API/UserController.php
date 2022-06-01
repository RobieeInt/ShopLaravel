<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Laravel\Fortify\Rules\Password;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'nullable|string|max:255',
                'password' => 'required|string|min:6',
            ]);

            User::create([
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password')),
            ]);

            $user = User::where('email', $request->input('email'))->first();

            $tokenResult = $user->createToken('Personal Access Token')->plainTextToken;

            return ResponseFormatter::success(
                ['token' => $tokenResult,
                 'user' => $user,
                 'token_type' => 'Bearer'],
                [ 'User berhasil ditambahkan']

            );
        } catch (\Exception $e) {
            return ResponseFormatter::error(
                null,
                $e->getMessage(),
                500
            );
        }
    }

    public function login(Request $request) {
        try {
            $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',
            ]);

            $credentials = request(['email', 'password']);
            if(!Auth::attempt($credentials)) {
                return ResponseFormatter::error(
                    ['message' => 'Invalid credentials'],
                    'Email atau password salah',
                    401
                );
            }

            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                return ResponseFormatter::error(
                    null,
                    'Email tidak ditemukan',
                    404
                );
            }

            if (!Hash::check($request->input('password'), $user->password)) {
                return ResponseFormatter::error(
                    null,
                    'Password salah',
                    401
                );
            }

            $tokenResult = $user->createToken('Personal Access Token')->plainTextToken;

            return ResponseFormatter::success(
                ['token' => $tokenResult,
                 'user' => $user,
                 'token_type' => 'Bearer'],
                ['User berhasil Masuk']

            );
        } catch (\Exception $e) {
            return ResponseFormatter::error(
                null,
                $e->getMessage(),
                500
            );
        }
    }

    public function fetch (Request $request) {
        return ResponseFormatter::success(
            Auth::user(),
            'User berhasil ditemukan'
        );
    }

    public function updateProfil(Request $request) {
        try {
            $request->validate([
                'name' => 'nullable|string|max:255',
                'username' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255',
                'phone' => 'nullable|string|max:255',
                'password' => 'nullable|string', new Password,
            ]);


            $user = Auth::user();

            // kalo field kosong, pakai yang ada di database
            $user->name = $request->input('name', $user->name);
            $user->username = $request->input('username', $user->username);
            $user->email = $request->input('email', $user->email);
            $user->phone = $request->input('phone', $user->phone);

            if($request->input('password')) {
                $user->password = Hash::make($request->input('password', $user->password));
            }



            $data = $request->all();
            $user->save($data);

            return ResponseFormatter::success(
                $user,
                'User berhasil diubah'
            );
        } catch (\Exception $e) {
            return ResponseFormatter::error(
                null,
                $e->getMessage(),
                500
            );
        }
    }
}
