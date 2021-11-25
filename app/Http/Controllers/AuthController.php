<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $response = [
                'status' => true,
                'message' => 'Berhasil login',
                'data' => [
                    'token' => 'Bearer '.$user->createToken('auth_token')->accessToken,
                ],
            ];
             return response()->json($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => 'Unauthorization'
            ];

            return response()->json($response,401);
        }
    }


    public function register(Request $request) {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8'
        ]);

        if($validate->fails()){
            $response = [
                'status' => false,
                'message' => 'Registrasi Gagal',
                'errors' => $validate->errors()
            ];

            return response()->json($response, 422);
        }

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);

        $response = [
            'status' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'token' => 'Bearer '.$user->createToken('auth_token')->accessToken,
            ]
        ];

        return response()->json($response,200);
    }

    public function profile() {
        $user = Auth::user();

        return response([
            'status' => true,
            'message' => 'User login profile',
            'data' => $user
        ],200);
    }


    public function logout(Request $request) {
        $request->user()->token()->revoke();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Logout'
        ],200);

    }



}
