<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class AuthController extends Controller
{
  public function unauthorized()
  {
    return response()->json([
      'error' => 'Você precisa estar logado para acessar essas informações!'
    ], 401);
  }

  public function create(Request $request)
  {
    $array = ['error' => ''];

    $validator = Validator::make($request->all(), [
      'name' => 'required|min:4|max:100',
      'email' => 'required|email|unique:users,email|min:4|max:100',
      'cpf' => 'required|digits:11',
      'password' => 'required|min:3',
      'avatar' => 'file|mimes:jpg,png,jpeg'
    ]);

    if(!$validator->fails())
    {

      $name = $request->input('name');
      $email = $request->input('email');
      $cpf = $request->input('cpf');
      $password = $request->input('password');
      $avatar = $request->file('avatar');

      $user = new User();
      $user->name = $name;
      $user->email = $email;
      $user->cpf = $cpf;
      $user->password = password_hash($password, PASSWORD_DEFAULT);
      // Verify if user send avatar file, database optional default.jpg
      if($avatar) {
        // Edit photo after save in database
      }

      $user->save();

      $token = Auth::attempt(['email' => $email, 'password' => $password]);
      if($token)
      {
        $array['token'] = $token;
      } else {
        $array['error'] = "Ocorreu um erro!";
      }

    } else {
      $array['error'] = $validator->errors()->first();
    }

    return $array;
  }

  public function login(Request $request)
  {
    $array = ['error' => ''];

    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required'
    ]);

    if(!$validator->fails())
    {
      $email = $request->input('email');
      $password = $request->input('password');

      $token = Auth::attempt(['email' => $email, 'password' => $password]);

      if($token) {
        $array['token'] = $token;
      } else {
        $array['error'] = 'Usuário e/ou senha incorretos!';
      }

    } else {
      $array['error'] = $validator->errors()->first();
    }

    return $array;
  }

  public function logout()
  {
    Auth::logout();
    return ['error' => ''];
  }

  public function validateToken()
  {
    $array = ['error' => ''];

    $user = Auth::user();
    $user['avatar'] = url('/media/avatars/'.$user['avatar']);

    $array['user'] = $user;
    return $array;
  }

}
