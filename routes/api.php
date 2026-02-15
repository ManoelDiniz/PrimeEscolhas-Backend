<?php


use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{slug}', [ProductsController::class, 'showBySlug']);
Route::get('/blog-posts', [\App\Http\Controllers\BlogPostsController::class, 'index']);
Route::get('/blog-posts/{slug}', [\App\Http\Controllers\BlogPostsController::class, 'getBlogBySlug']);
Route::post('customer-contacts', [\App\Http\Controllers\CustomerContactController::class, 'store']);
Route::get('/categories', [\App\Http\Controllers\CategoriesController::class, 'index']);
