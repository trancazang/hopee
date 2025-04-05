<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AdminTestController;
use App\Livewire\Actions\Logout;
use App\Http\Controllers\ChatController;


Route::middleware('auth')->group(function() {
    // Hiển thị form + list hoặc chat panel
    Route::get('/chat/{id?}', [ChatController::class, 'index'])->name('chat.show');
    // Tạo conversation
    Route::post('/chat', [ChatController::class, 'storeConversation'])->name('chat.store');
    // Lấy tin nhắn (JSON)
    Route::get('/chat/{id}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    // Gửi tin nhắn
    Route::post('/chat/{id}/message', [ChatController::class, 'sendMessage'])->name('chat.message');
});
//Route::get('/chat', [ChatController::class, 'index'])->name('chat.show');

Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

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
