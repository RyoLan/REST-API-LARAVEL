<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        // $credentials = $request->validate([
        //     'email' => ['required', 'email'],
        //     'password' => ['required'],
        // ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message'=> 'Gagal Login'
            ], 401);
        }

        $user = User::where('email', $request->email )->firstOrFail();
        $token = $user ->createToken('auth_token')->plainTextToken;

        // if (Auth::attempt($credentials)) {
        //     $request->session()->regenerate();

        //     return redirect()->intended('dashboard');
        // }

        return response()->json([
            'status'=> true,
            'data' => $user,
            'access_token' => $token,
            'message' => 'Login Berhasil'
        ], 200);

        // return back()->withErrors([
        //     'email' => 'The provided credentials do not match our records.',
        // ])->onlyInput('email');
    }

    public function logout(Request $request){
        Auth()->user()->tokens()->delete();

        return response()->json([
            'status'=> true,
            'message'=> 'Logut Berhasil'
        ], 200);
    }

    public function register(Request $request){
        $validate = validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email'=> 'required|string|max:255|unique:users',
            'password'=> 'required|string|min:8',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);

        return response()->json([
            // 'status'=> true,
            'data' => $user,
            'success' => true,
            'message' => 'User Berhasil Dibuat'
        ]);
    }

}
