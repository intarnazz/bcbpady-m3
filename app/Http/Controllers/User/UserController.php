<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  public function reg(Request $request)
  {
    $res = [
      "success" => false,
      "message" => [],
    ];

    $user = User::all()
      ->where("email", $request->email)
      ->first();

    if ($user) {
      return response()->json($res)->setStatusCode(400);
    }
    $user = new User();
    $user->first_name = $request->first_name;
    $validator = Validator::make($request->all(), [
      "first_name" => ["required", "string", ""],
    ]);
    if ($validator->fails()) {
      $res['message'][] = "error first_name";
    }


    $user->last_name = $request->last_name;
    $validator = Validator::make($request->all(), [
      "last_name" => ["required", "string", ""],
    ]);
    if ($validator->fails()) {
      $res['message'][] = "error last_name";
    }


    $user->email = $request->email;
    $validator = Validator::make($request->all(), [
      "email" => ["required", "email", ""],
    ]);
    if ($validator->fails()) {
      $res['message'][] = "error email";
    }


    $user->password = $request->password;
    $validator = Validator::make($request->all(), [
      "password" => ["required", "string", ""],
    ]);
    if ($validator->fails()) {
      $res['message'][] = "error password";
    }

    if ($res['message']) {
      return response()->json($res)->setStatusCode(400);
    }

    $user->save();

    $user->remember_token = $user->createToken("remember_token")->plainTextToken;
    $user->save();

    $res = [
      "success" => true,
      "message" => "Success",
      "token" => $user->remember_token
    ];
    return response()->json($res)->setStatusCode(200);
  }

  public function login(Request $request)
  {
    $res = [
      "success" => false,
      "message" => "Login failed",
    ];

    $token = $request->header("Authorization");
    $token = str_replace("Bearer ", "", $token);

    $user = User::all()
      ->where("email", $request->email)
      ->where("password", $request->password)
      ->first();
    if (!$user) {
      return response()->json($res)->setStatusCode(400);
    }

    $user->remember_token = $user->createToken("remember_token")->plainTextToken;
    $user->save();

    $res = [
      "success" => true,
      "message" => "Success",
      "token" => $user->remember_token
    ];
    return response()->json($res)->setStatusCode(200);
  }
  public function logout(Request $request)
  {
    $res = [
      "success" => false,
      "message" => "Login failed",
    ];

    $token = $request->header("Authorization");
    $token = str_replace("Bearer ", "", $token);

    $user = User::all()
      ->where("remember_token", $token)
      ->first();
    if (!$user) {
      return response()->json($res)->setStatusCode(400);
    }

    $user->remember_token = '';
    $user->save();

    $res = [
      "success" => true,
      "message" => "Logout",
    ];
    return response()->json($res)->setStatusCode(200);
  }
  public function get(Request $request)
  {
    $res = [
      "success" => false,
      "message" => "Login failed",
    ];

    $token = $request->header("Authorization");
    $token = str_replace("Bearer ", "", $token);
    $user = User::all()
      ->where("remember_token", $token)
      ->first();
    if (!$user) {
      return response()->json($res)->setStatusCode(400);
    }

    $res = [
      "success" => true,
      "message" => "Success",
      "user" => $user
    ];
    return response()->json($res)->setStatusCode(200);
  }
}
