<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class SearchController extends Controller
{
  public function searchProduct(Request $request)
  {
    $array = ['error' => ''];

    $validator = Validator::make($request->all(), [
      'searchTerm' => 'required|string'
    ]);

    if(!$validator->fails())
    {
      $searchTerm = $request->input('searchTerm');
      $productList = Product::where('title', 'like', '%'.$searchTerm.'%')->get();
      $array['list'] = $productList;
    } else
    {
      $array['error'] = $validator->errors()->first();
    }

    return $array;
  }
}
