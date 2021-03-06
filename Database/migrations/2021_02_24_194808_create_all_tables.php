<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('email')->unique();
      $table->string('cpf')->unique();
      $table->string('password');
      $table->string('phone');
      $table->string('avatar')->default('default.jpg');
    });

    Schema::create('categories', function (Blueprint $table) {
      $table->id();
      $table->string('name');
    });

    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->integer('id_category');
      $table->integer('id_user');
      $table->string('title');
      $table->string('description');
      $table->float('price');
    });

    Schema::create('photos', function (Blueprint $table) {
      $table->id();
      $table->integer('id_product');
      $table->string('url');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('users');
    Schema::dropIfExists('categories');
    Schema::dropIfExists('products');
    Schema::dropIfExists('photos');
  }
}
