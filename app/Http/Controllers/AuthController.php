<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\User;
use Hash;
use JWTAuth;


class AuthController extends Controller
{
    public function authenticate(Request $request){
        //Validate fields
        $this->validate($request,['email' => 'required|email','password'=> 'required']);
        //Attempt validation
        $credentials = $request->only(['email','password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json(compact('token'));
    }
    public function register(Request $request){
        //Validate fields
        $this->validate($request,[
            'email' => 'required|email|max:255|unique:users',
            'name' => 'required|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        //Create user, generate token and return
        $user =  User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);        
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('token'));
    }
}
