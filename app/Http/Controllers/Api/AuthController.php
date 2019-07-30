<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Logger;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTFactory;

class AuthController extends Controller
{

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->save();

        // $token = auth('api')->login($user);

        // return $this->respondWithToken($token);

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    
    }

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (empty($request->email) || empty($request->password)) {

            return response()->json(['error' => 'Unauthorized'], 422);

        }

        if (! $token = auth('api')->attempt($credentials)) {

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth('api')->user();
        // dd($user);

        $log = new Logger;
        $log->description = 'User : '.$user->name.', logged in';
        $log->username = $user->name;
        $log->model = 'Auth';
        $log->save();

        $user->update(['last_login' => date('m/d/Y')]);

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            // 'token_type'   => 'bearer',
            // 'expires_in'   => auth('api')->factory()->getTTL() * 60
            'user_name' => auth('api')->user()->name,
            'user_role' => auth('api')->user()->role
        ]);
    }

}
