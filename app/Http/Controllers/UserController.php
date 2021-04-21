<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'first_name' => 'required|alpha|min:2|max:20',
            'last_name' => 'required|alpha|min:2|max:20',
            'username' => 'required|alpha|numeric|regex:/^[._]+$/|min:5|max:12',
            'password' => 'required|min:5|max:12',
        ]);

        if ($validator->fails())
        {
            return response(['message' => 'invalid field'], 422);
        }

        $data = $validator->validated();

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        $token = $user->tokens()->create(['token' => bcrypt(auth()->id())]);

        return response([
            'token' => $token->token,
        ]);
    }

    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails())
        {
            return response(['message' => 'invalid field'], 422);
        }

        if (auth()->attempt($validator->validated()))
        {
            $token = auth()->user()->tokens()->create(['token' => bcrypt(auth()->id())]);

            return response([
                'token' => $token->token,
            ]);
        }

        return response(['message' => 'invalid login'], 401);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->where(['token' => $request->query('token')])->delete();
    }
}
