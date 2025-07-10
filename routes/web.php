<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArticleController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::post('/article', [ArticleController::class, 'store'])->name('articles.store');

Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
Route::put('/articles/{id}', [ArticleController::class, 'update'])->name('articles.update');
Route::resource('articles', ArticleController::class);

Route::resource('/article', ArticleController::class)->names([
    'index' => 'article.index',
    'create' => 'article.create',
    'store' => 'articles.store',
    'edit' => 'articles.edit',
    'update' => 'articles.update',
    'destroy' => 'articles.destroy',
    'show' => 'articles.show',
]);

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
