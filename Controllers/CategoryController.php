<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Category;

class CategoryController extends Controller
{
  public function insert(Request $request)
  {
    $array = ['error' => ''];

    $validator = Validator::make($request->all(), [
      'name' => 'required|min:3|max:100'
    ]);

    if(!$validator->fails())
    {

      $name = $request->input('name');

      $category = new Category();
      $category->name = $name;
      $category->save();

    } else {
      $array['error'] = $validator->errors()->first();
    }

    return $array;
  }

  public function delete($id)
  {
    $array = ['error' => ''];

    $category = Category::find($id);
    if($category)
    {

      $category->delete();
      $array['success'] = 'Categoria Deletada com sucesso!';

    }
    else {
      $array['error'] = 'Categoria não existe!';
    }

    return $array;
  }

}
