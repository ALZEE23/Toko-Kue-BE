<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email','password');

        if(!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'email atau password salah',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'user'     => auth()->guard('api')->user()->only(['name','email','id']),
            'permission' => auth()->guard('api')->user()->getPermissionArray(),
            'message' => 'berhasil',
            'token' => $token
        ], 200);
    
    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'berhasil logout'
        ], 200);
    }

    
}
