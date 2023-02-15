<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function sign_up(Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'role_id' => 'required',
            'password' => 'required|string|confirmed',
        ]);

        // {
        //     "name":"Jose Marin",
        //     "email": "josemarin@email.com",
        //     "password":"123456",
        //     "password_confirmation":"123456"
        // }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id'=>$data['role_id'],
            'password' => bcrypt($data['password'])
        ]);

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];
        return response($res, 201);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // {
        //     "email": "josemarin@email.com",
        //     "password":"123456"
        // }

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'msg' => 'incorrect username or password'
            ], 401);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];

        return response($res, 201);
    }
    
    public function logout(Request $request)
    {
         // Get bearer token from the request
        $accessToken = $request->bearerToken();

        // Get access token from database
        $token = PersonalAccessToken::findToken($accessToken);

        // Revoke token
        $token->delete();

        return ['message' => $request->user()];
    }



    
}
