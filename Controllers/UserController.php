<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

use App\Models\User;

class UserController extends Controller
{
  public function updateAvatar(Request $request)
  {
    $array = ['error' => ''];
    // Tipos permitidos de arquivo imagem.
    $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

    $image = $request->file('avatar');

    if($image) {
      if(in_array($image->getClientMimeType(), $allowedTypes)) {

        $fileName = md5(time().rand(0, 9999)).'.jpg';
        // Pasta para salvar
        $destPath = public_path('/media/avatars');

        // Image crop using library Intervation Image Laravel Integration.
        $img = Image::make($image->path())
          ->fit(200, 200)
          ->save($destPath.'/'.$fileName);

          $user = Auth::user();
          $newUser = User::find($user['id']);

          $newUser->avatar = $fileName;
          $newUser->save();

          // Retornando nome do arquivo(avatar user) salvo
          $array['url'] = url('/media/avatars/'.$fileName);

      } else {
        $array['error'] = 'Arquivo não suportado pelo sistema!';
      }

    } else {
      $array['error'] = 'Avatar não enviado!';
    }

    return $array;
  }
}
