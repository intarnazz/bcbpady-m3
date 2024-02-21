<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\File;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
  public function add(Request $request)
  {
    $host = "http://bcbpady-m3.wsr.ru/";

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
    // return response()->json($token)->setStatusCode(200);
    if (!$user) {
      return response()->json($res)->setStatusCode(400);
    }

    $file = new File();
    $file->user_id = $user->user_id;

    foreach ($request->file('') as $file) {
      $name = $file->getClientOriginalName();
      $file->storeAs('file', $name);
      $res[] = [
        "success" => true,
        "message" => "Success",
        "name" => $file->getClientOriginalName(),
        "url" => $host . "files/" . $name
      ];
    }

    return response()->json($res)->setStatusCode(200);
  }
}
