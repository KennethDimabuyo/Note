<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function Login(Request $req)
    {
        $fields = $req->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($req->only('username', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('myapptoken')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        }
        throw ValidationException::withMessages([
            'password' => ['Incorrect credentials']
        ]);
    }

    public function Register(Request $req) 
    {
        $req->validate([
            'username' => 'required|string|unique:users',
            'password' => 'required|string|confirmed',
            'email' => 'email|string',
        ]);
        
        $user = User::create([
            'name' => $req->name,
            'username' => $req->username,
            'email' => $req->email,
            'password' => bcrypt($req->password),
        ]);

        if ($user) {
            if (Auth::attempt($req->only('username', 'password'))) {
                $token = $user->createToken('myapptoken')->plainTextToken;
                return response()->json([
                    'user' => $user,
                    'token' => $token
                ]);
            }
        }
        return response()->json(false);
    }
}
