<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Hash;

class UsersController extends Controller
{
    public static function register(Request $request)
    {
        $rules = [
            'username' => 'required|string|min:3|unique:flex_users',
            'password' => 'required|string|min:8'
        ];
        $validation = ValidateHttpRequest($rules, $request->all(), true);
        if ($validation) {
            return response()->json(array_merge($validation));
        }
        $password = Hash::make($request->get('password'));

        $user = User::insert(['username' => $request->get('username'), 'password' => $password]);

        if ($user) {
            return response()->json(["success" => true, "message" => "User successfully registered."], 200);
        }

        return response()->json(["success" => false, "message" => "Something went wrong"], 400);
    }

    public function login(Request $request)
    {
        $rules = [
            'username' => 'required|string|min:3',
            'password' => 'required|string',
        ];

        $validation = ValidateHttpRequest($rules, $request->all(), true);

        if ($validation) {
            return response()->json(array_merge($validation));
        }

        if (!Auth::attempt($request->all())) {
            return response()->json([
                'success' => false,
                'message' => 'Wrong login credentials.',
            ], 200);
        }

        Carbon::setLocale(config('app.locale'));
        $user = $request->user();
        $tokenResult = $user->createToken('authToken');
        $token = $tokenResult->token;
        if (!$request->remember_me) {
            $token->expires_at = Carbon::now()->addHour();
        }
        $token->save();

        return response()->json([
            'success' => true,
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

}
