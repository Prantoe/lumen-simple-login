<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
      public function register(Request $request)
      {
            $name = $request->input('name');
            $email = $request->input('email');
            $password = Hash::make($request->input('password'));
            $token = $request->input('api_token', Str::random(40));

            $register = User::create(
                  [
                        'name' => $name,
                        'email' => $email,
                        'password' => $password,
                        'api_token' => $token
                  ]
            );
            if ($register) {
                  return response()->json([
                        'success' => true,
                        'message' => 'Register Successed!',
                        'data' => $register
                  ], 201);
            } else {
                  return response()->json([
                        'success' => false,
                        'message' => 'Register Failed!',
                        'data' => ''
                  ], 404);
            }
      }

      public function login(Request $request)
      {
            $email = $request->input('email');
            $password = $request->input('password');

            $user = User::where('email', $email)->first();

            if (Hash::check($password, $user->password)) {
                  $apiToken = base64_encode(Str::random(40));

                  $user->update([
                        'api_token' => $apiToken
                  ]);

                  return response()->json([
                        'success' => true,
                        'message' => 'login success!',
                        'data' => [
                              'user' => $user,
                              'api_token' => $apiToken
                        ]
                  ], 201);
            } else {
                  return response()->json([
                        'success' => false,
                        'message' => 'login failled!',
                        'data' => ''
                  ], 404);
            }
      }

      public function logout(Request $request)
      {
      }
}
