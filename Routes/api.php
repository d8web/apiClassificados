<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;

Route::post('/auth/register', [AuthController::class, 'create']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

Route::middleware('auth:api')->group(function() {
  Route::post('/auth/logout', [AuthController::class, 'logout']);
  Route::post('/auth/validate', [AuthController::class, 'validateToken']);

  Route::post('/category', [CategoryController::class, 'insert']);
  Route::delete('/category/{id}', [CategoryController::class, 'delete']);

  Route::get('/user/products', [ProductController::class, 'getProductsFromUserLogged']); // User logged products list
  Route::get('/products', [ProductController::class, 'readAllProducts']); // Read all products
  Route::post('/product/{id_category}', [ProductController::class, 'addProduct']); // Add product id_user = user logged
  Route::put('/product/{id}', [ProductController::class, 'editProduct']); // Edit Product from user logged

  // Continue this router delete product
  Route::delete('/product/{id}', [ProductController::class, 'deleteProduct']); // Delete products from user logged

  Route::post('/product/{id_product}/photo', [ProductController::class, 'addPhoto']);
  Route::post('/search', [SearchController::class, 'searchProduct']);

  // User Routes
  Route::post('/user/updateavatar', [UserController::class, 'updateAvatar']);

  // Add more routes before
});
