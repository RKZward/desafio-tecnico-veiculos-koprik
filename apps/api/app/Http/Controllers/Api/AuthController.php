<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $data = $req->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'confirmed', 'min:6'],
        ]);

        $email = Str::lower(trim($data['email']));

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $email,
            'password' => Hash::make($data['password']),
        ]);

        $tokenName = 'api:' . ($req->userAgent() ?: 'unknown');
        $token = $user->createToken($tokenName)->plainTextToken;
        return response()->json(['token' => $token], 201);
    }
    public function login(Request $req)
    {
        $cred = $req->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = Str::lower(trim($cred['email']));
        $user  = User::where('email', $email)->first();

        if (!$user || !Hash::check($cred['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ])->status(401);
        }
        $tokenName = 'api:' . ($req->userAgent() ?: 'unknown');
        $token = $user->createToken($tokenName)->plainTextToken;
        return response()->json(['token' => $token], 200);
    }

    public function me(Request $req)
    {
        $u = $req->user();

        return response()->json([
            'id'    => $u->id,
            'name'  => $u->name,
            'email' => $u->email,
        ]);
    }

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()?->delete();
        return response()->noContent(); // 204
    }
}
