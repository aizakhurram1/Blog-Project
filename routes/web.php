<?php

use App\Http\Controllers\Auth\MagicLoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/verify-registration', [RegisteredUserController::class, 'verifyEmail'])
    ->name('verify.registration')
    ->middleware('signed');
Route::post('/complete-registration', [RegisteredUserController::class, 'completeRegistration'])
    ->name('complete.registration');

Route::get('/login', [MagicLoginController::class, 'showForm'])->name('login');
Route::post('/magic-login', [MagicLoginController::class, 'requestLink'])->name('magic.login.request');
Route::get('/magic-login/{token}', [MagicLoginController::class, 'loginViaToken'])->name('magic.login.token');

require __DIR__.'/auth.php';

Route::get('/', [PostController::class, 'home'])->name('home');
Route::get('/about-us', [SiteController::class, 'about'])->name('about-us');
Route::get('/category/{category:slug}', [PostController::class, 'byCategory'])->name('by-category');
Route::get('/{post:slug}', [PostController::class, 'show'])->name('view');
