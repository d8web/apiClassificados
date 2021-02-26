<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use App\Models\Product;
use App\Models\Category;
use App\Models\Photo;

class ProductController extends Controller
{
  private $loggedUser;

  public function __construct()
  {
    $this->loggedUser = Auth::user();
  }

  public function addProduct($id_category, Request $request)
  {
    $array = ['error' => ''];

    $category = Category::find($id_category);
    if($category)
    {
      $validator = Validator::make($request->all(), [
        'title' => 'required|min:3|max:200',
        'description' => 'required|min:3|max:200',
        'price' => 'required'
      ]);

      if(!$validator->fails())
      {
        $title = $request->input('title');
        $description = $request->input('description');
        $price = $request->input('price');

        $product = new Product();
        $product->id_user = $this->loggedUser['id'];
        $product->id_category = $id_category;
        $product->title = $title;
        $product->description = $description;
        $product->price = $price;
        $product->save();
      } else
      {
        $array['error'] = $validator->errors()->first();
      }
    } else {
      $array['error'] = 'Categoria não existe!';
    }

    return $array;
  }

  public function readAllProducts()
  {
    $array = ['error' => ''];
    $products = Product::all();

    // Get photos from product to add array
    foreach($products as $prodKey => $prodValue)
    {
      $products[$prodKey]['photos'] = Photo::select(['url'])
        ->where('id_product', $prodValue['id'])
        ->get();
    }

    // Straighten the Link photo complete
    foreach($products as $prodKey => $item)
    {
      foreach($products[$prodKey]['photos'] as $pItem) {
        $pItem['url'] = url('media/uploads/'.$pItem['url']);
      }
    }

    $array['list'] = $products;
    return $array;
  }

  public function addPhoto($id_product, Request $request)
  {
    $array = ['error' => ''];

    $product = Product::find($id_product);
    if($product)
    {

      $validator = Validator::make($request->all(), [
        'photo' => 'required|file|mimes:jpg,png,jpeg'
      ]);

      if(!$validator->fails()) {

        $file = $request->file('photo');
        $fileName = md5(time().rand(0, 9999)).'.jpg';
        // Pasta para salvar
        $destPath = public_path('/media/products/');

        $img = Image::make($file->path())
          ->fit(500, 500)
          ->save($destPath.'/'.$fileName);

        $photo = new photo();
        $photo->id_product = $id_product;
        $photo->url = $fileName;
        $photo->save();

        // Retornando nome do arquivo(avatar user) salvo
        $array['url'] = url('/media/products/'.$fileName);

      } else {
        $array['error'] = $validator->errors()->first();
      }

    } else {
      $array['error'] = 'Product not found';
    }

    return $array;
  }

  public function editProduct($id, Request $request)
  {
    $array = ['error' => ''];

    // To do
    $product = Product::find($id);
    if($product)
    {
      $id_user = $this->loggedUser['id'];
      $id_category = $request->input('id_category');
      $title = $request->input('title');
      $description = $request->input('description');
      $price = $request->input('price');

      $product->id_user = $id_user;

      if($id_category) {
        $product->id_category = $id_category;
      }

      if($title) {
        $product->title = $title;
      }

      if($description) {
        $product->description = $description;
      }

      if($price) {
        $product->price = $price;
      }
      $product->save();

    } else {
      $array['error'] = 'Produto não encontrado!';
    }

    return $array;
  }

  public function getProductsFromUserLogged()
  {
    $array = ['error' => ''];

    $products = Product::where('id_user', $this->loggedUser['id'])->get();
    foreach($products as $pKey => $pValue)
    {
      $products[$pKey]['photos'] = Photo::select(['url'])->where('id_product', $pValue['id'])->get();
    }

    foreach($products as $prodKey => $item)
    {
      foreach($products[$prodKey]['photos'] as $pItem) {
        $pItem['url'] = url('media/uploads/'.$pItem['url']);
      }
    }

    $array['products'] = $products;
    return $array;
  }

}
