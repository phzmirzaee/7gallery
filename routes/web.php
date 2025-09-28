<?php

use Illuminate\Support\Facades\Route;
use  App\Models\Category;

Route::get('/', function () {
    return view('welcome');
});

Route::get('create/category', function () {
    dd(Category::find(1)->update(["title"=>"7lern"]));

});
