<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
      public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        // Try to create token
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        $user = User::where('email', $credentials['email'])
                        ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                "message"=>"Login was successful.",
                "data"=>[
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_id' => $user->role->id,
                    'role' => $user->role->name ?? null,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
                
            ]);
        } else {
           return response()->json([
                'message' => 'Password is incorrect.'
            ], 404);
        }
        
    }
    public function register(Request $request)
    {
        try {
        $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role_id'=>'required|integer|exists:roles,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        // return $request;
        // 2. Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);
        // return $user;
        // 3. Generate token
        $token = JWTAuth::fromUser($user);
        

        // 4. Return response
        return response()->json([
            'message' => 'Registeration is successful.',
            "data"=>[
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $user->role->id,
                'role' => $user->role->name ?? null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ], 201);
    }
}
