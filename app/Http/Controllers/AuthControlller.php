<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'password'=>$request->password,
        ]);

        return response()->json(['message'=>'user created successfully']);
    }

    public function login_user(Request $request){

        $auth = $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);

        if(!Auth::attempt($auth)){
            return response()->json('crediental not match!');
        }


        return response('logged in',200);
    }
}
