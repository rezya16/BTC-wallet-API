<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            echo 'error';
        }
        $validatedData = $request->all();

        $validatedData['password'] =  Hash::make($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(['token' => $accessToken], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json(['access_token' => $accessToken], Response::HTTP_OK);

    }
}
