<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\ChangePasswordRequest;
use App\Models\User;
use App\Models\Order;
use App\Models\Point;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\OrderResource;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Api\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->birthday = $request->birthday;
            $user->address = $request->address;
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
            $point_history = Point::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

            $response = [
                'message' => 'success register',
                'user' => $user,
                'point' => $point,
                'token' => $token,
                'point_history' => $point_history
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
        $point_history = Point::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        if($user) {
            $order = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        }

        $response = [
            'message' => 'success login',
            'user' => $user,
            'point' => $point,
            'token' => $token,
            'point_history' => $point_history,
            'order' => $user ? OrderResource::collection($order->load('customer')) : null
        ];

        return response()->json(['response' => $response], 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'success logout']);
    }

    public function changePassword(ChangePasswordRequest $request) {
        $user = User::find($request->id);
        if(!$user || !Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Current password is not correct'], 401);
        }

        $user->password = Hash::make($request->password);
        $user->update();

        return response()->json(['status' => 'success', 'message' => 'Password changed']);
    }
}
