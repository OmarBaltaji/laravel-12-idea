<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\IdeaController;

// Route::get('/', fn () => view('welcome'));

// Welcome page is where marketing is done to encourage users to signup
Route::redirect('/', 'ideas');

Route::middleware('guest')->group(function() {
  Route::get('/register', [RegisteredUserController::class, 'create']);
  Route::post('/register', [RegisteredUserController::class, 'store']);
  
  Route::get('/login', [SessionsController::class, 'create'])->name('login');
  Route::post('/login', [SessionsController::class, 'store']);
});

Route::middleware('auth')->group(function() {
  Route::get('/ideas', [IdeaController::class, 'index'])->name('ideas.index');
  Route::get('/ideas/{idea}', [IdeaController::class, 'show'])->name('ideas.show');
  Route::post('/logout', [SessionsController::class, 'destroy']);
});