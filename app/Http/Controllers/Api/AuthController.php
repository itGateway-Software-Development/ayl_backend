<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Api\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $user = new User();
        $user->fill([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('name')),
            'birthday' => $request->input('birthday'),
            'address' => $request->input('address')
        ]);
        $user->save();

        $token = $user->createToken('romanticunderwear')->plainTextToken;

        $response = [
            'message' => 'success register',
            'user' => $user,
            'token' => $token
        ];

        return response()->json(['response' => $response], 201);
    }

    public function login(LoginRequest $request) {
        $user = User::where('email', $request->input('email'))->first();

        if(!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json(['message' => 'wrong credentials'], 401);
        }

        $token = $user->createToken('romanticunderwear')->plainTextToken;

        $response = [
            'message' => 'success login',
            'user' => $user,
            'token' => $token
        ];

        return response()->json(['response' => $response], 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'success logout']);
    }
}
