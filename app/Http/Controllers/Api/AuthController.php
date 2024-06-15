<?php

namespace App\Http\Controllers\Api;

use App\Models\Point;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Api\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        DB::beginTransaction();
        logger($request->all());
        try {
            $user = new User();
            $user->fill([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'birthday' => $request->birthday,
                'address' => $request->address
            ]);
            $user->save();

            $token = $user->createToken('romanticunderwear')->plainTextToken;

            $point = new Point();
            $point->type = 'in';
            $point->name = 'Login Bonus';
            $point->reason = $user->name. ' login at '. $user->created_at;
            $point->points = 500;
            $point->total = 500;
            $point->user_id = $user->id;
            $point->save();

            $point = Point::where('user_id', $user->id)->latest()->first()->total;


            $response = [
                'message' => 'success register',
                'user' => $user,
                'point' => $point,
                'token' => $token
            ];

            DB::commit();

            return response()->json(['response' => $response], 201);
        } catch(\Exception $error) {
            logger($error);
            DB::rollBack();

            return response()->json(['message' => 'register error', 'error' => $error->getMessage()],400);
        }
    }

    public function login(LoginRequest $request) {
        $user = User::where('email', $request->input('email'))->first();

        if(!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json(['message' => 'wrong credentials'], 401);
        }

        $token = $user->createToken('romanticunderwear')->plainTextToken;

        $point = Point::where('user_id', $user->id)->latest()->first()->total;

        $response = [
            'message' => 'success login',
            'user' => $user,
            'point' => $point,
            'token' => $token
        ];

        return response()->json(['response' => $response], 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'success logout']);
    }
}
