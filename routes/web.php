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
