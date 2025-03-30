<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AdminTestController;
use App\Livewire\Actions\Logout;

Route::post('/logout', Logout::class)->name('logout');

Route::view('/', 'welcome')->name('welcome');;

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/tests', [QuizController::class, 'index'])
    ->name('tests.index')
    ->middleware('auth'); // Yêu cầu đăng nhập

Route::get('/tests/{test}', [QuizController::class, 'show'])
    ->name('tests.show')
    ->middleware('auth');

Route::post('/tests/{test}/submit', [QuizController::class, 'submit'])
    ->name('tests.submit')
    ->middleware('auth');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/tests', [AdminTestController::class, 'index'])->name('admin.tests.index');
    Route::get('/tests/create', [AdminTestController::class, 'create'])->name('admin.tests.create');
    Route::post('/tests', [AdminTestController::class, 'store'])->name('admin.tests.store');
    Route::get('/tests/{test}/edit', [AdminTestController::class, 'edit'])->name('admin.tests.edit');
    Route::put('/tests/{test}', [AdminTestController::class, 'update'])->name('admin.tests.update');
    Route::delete('/tests/{test}', [AdminTestController::class, 'destroy'])->name('admin.tests.destroy');
});

require __DIR__.'/auth.php';
