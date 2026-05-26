<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\Api\BlogsApiController;

Route::group(['prefix' => 'v1'], function () {
    // Blogs
    Route::get('blogs', [BlogsApiController::class, 'index'])->name('blogs.index');
    Route::get('blogs/popular', [BlogsApiController::class, 'popular'])->name('blogs.popular');
    Route::get('blogs/{blogId}/similar', [BlogsApiController::class, 'similar'])->name('blogs.similar');
    Route::post('blogs/{blogId}/click', [BlogsApiController::class, 'incrementClick'])->name('blogs.click');
});
