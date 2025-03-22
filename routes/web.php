<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

Route::get('/', function () {
    return view('blogs.index');
});

Route::get('blog/dataTable', [BlogController::class, 'serverSideTable'])->name('blog.dataTable'); // Ensure this route is defined
Route::post('blog/store', [BlogController::class, 'store'])->name('blog.store');
Route::resource('blog', BlogController::class);
