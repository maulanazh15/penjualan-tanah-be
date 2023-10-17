<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index() {
        $users = User::where('id','!=', auth()->user()->id)->get();

        return $this->success($users);
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'username' => $request->input('username'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return $this->success([
            'user' => new UserResource($user),
        ], "User has been register succesfully", 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->input('username'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => new UserResource($user),
        ], "User Login Successfully");
    }

    public function get(Request $request)
    {
        $user = $request->user(); // The authenticated user
        
        if (!$user) {
           return $this->error('User not authenticated', 401);
        }

        return $this->success([
            'user' => new UserResource($user)
        ],'Get current user success', 200);
    
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete();
        }
        return $this->success(null, 'User logged out successfully');
    }
}
