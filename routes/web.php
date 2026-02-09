<?php

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\IdeaImageController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\StepController;
use Illuminate\Support\Facades\Route;

// Route::get('/', fn () => view('welcome'));

// Welcome page is where marketing is done to encourage users to signup
Route::redirect('/', 'ideas');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/login', [SessionsController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/ideas', [IdeaController::class, 'index'])->name('ideas.index');
    Route::post('/ideas', [IdeaController::class, 'store'])->name('ideas.store');

    // Route::middleware('can:workWith,idea')->group(function() {
    Route::can('workWith', 'idea')->group(function() {
        Route::get('/ideas/{idea}', [IdeaController::class, 'show'])->name('ideas.show');
        Route::patch('/ideas/{idea}', [IdeaController::class, 'update'])->name('ideas.update');
        Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy'])->name('ideas.delete');
        Route::delete('/ideas/{idea}/image', [IdeaImageController::class, 'destroy'])->name('ideas.image.destroy');
    });
    Route::patch('/steps/{step}', [StepController::class, 'update'])->name('steps.update');
    Route::post('/logout', [SessionsController::class, 'destroy']);
});
