<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SliderMediaController;


// ==================== AUTH ====================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

// Login & Register
Route::get('/login', fn() => view('login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// ==================== PROTECTED ROUTES ====================
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Articles
    Route::resource('articles', ArticleController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::post('/settings/security', [SettingsController::class, 'updateSecurity'])->name('settings.security.update');
    Route::post('/settings/appearance', [SettingsController::class, 'updateAppearance'])->name('settings.appearance.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', function () {
        if (!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized');
        }
        return view('admin.dashboard');
    });

    Route::get('/editor', function () {
        if (!auth()->user()->isEditor()) {
            abort(403, 'Unauthorized');
        }
        return 'Editor Area';
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole')->middleware('can:manage-users');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('can:manage-users');
});

