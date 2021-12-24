<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthControlller extends Controller
{
    public function create_user(Request $request){

        $request->validate([
            'username'=>'required',
            'password'=>'required',
            'name' => 'required',
            'email' => 'required',
        ]);

        User::create([
            'name'=>$request->name,
            'email'=> $request->email,
            'username'=>$request->username,
            'password'=>Hash::make($request->password),
        ]);

        return response()->json(['message'=>'user created successfully']);
    }

    public function login_user(Request $request){

        $attr = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6'
        ]);


        if (!Auth::attempt($attr)) {
            return response('Credentials not match', 401);
        }

        return response()->json([
            'id' => auth()->user()->id,
            'user' => auth()->user()->username,
            'token' => $this->auth()->user()->createToken('API Token')->plainTextToken,
        ]);

    }

    public function logout_user(){
        auth()->user()->tokens()->delete();

        return response()->json('Log out successfully!.');
    }
}
