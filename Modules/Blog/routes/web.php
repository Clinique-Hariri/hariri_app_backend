<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\AuthorsController;
use Modules\Blog\Http\Controllers\BlogsController;
use Modules\Blog\Http\Controllers\CategoriesController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::resource('blog/authors', AuthorsController::class)->names('blog.authors');
    Route::resource('blog/categories', CategoriesController::class)->names('blog.categories');
    Route::resource('blogs', BlogsController::class);
});
