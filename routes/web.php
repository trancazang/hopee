<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AdminTestController;
use App\Livewire\Actions\Logout;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForumSearchController;
 


Route::get('/forum/search', [ForumSearchController::class, 'index'])->name('forum.search');
Route::get('/profile', [UserController::class, 'edit'])
    ->middleware(['auth'])
    ->name('profile');

Route::post('/profile/avatar', [UserController::class, 'updateAvatar'])
    ->middleware(['auth'])
    ->name('profile.avatar.update');


Route::middleware('auth')->group(function() {
    // Hiển thị form + list hoặc chat panel
    Route::get('/chat/{id?}', [ChatController::class, 'index'])->name('chat.show');
    // Tạo conversation
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
    // Lấy tin nhắn (JSON)
    Route::get('/chat/{id}/messages', [ChatController::class, 'messages'])->name('chat.messages');
    // Gửi tin nhắn
    Route::post('/chat/{id}/message', [ChatController::class, 'message'])->name('chat.message');
    //xoá tin nhắn
    Route::delete('/chat/{conv}/message/{msg}', [ChatController::class,'destroyMessage'])
         ->name('chat.message.destroy');

    // Xóa conversation (clear cho participant)
    Route::delete('/chat/{conv}', [ChatController::class,'destroyConversation'])
         ->name('chat.destroy');
    Route::post('chat/{conversation}/read',
         [ChatController::class, 'markRead'])->name('chat.read');
});
//Route::get('/chat', [ChatController::class, 'index'])->name('chat.show');
// routes/web.php
    Route::get('/chat/{conversation}/participants',
           [ChatController::class,'participants'])
      ->middleware('auth');

      Route::middleware('auth')->group(function () {
        Route::get('/chat/{conversation}/participants', [ChatController::class, 'participants']);
        Route::post('/chat/{conversation}/member', [ChatController::class, 'addMember']);
        Route::delete('/chat/{conversation}/member/{userId}', [ChatController::class, 'removeMember']);
        
    });
    
    



Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

Route::post('/logout', Logout::class)->name('logout');

Route::view('/', 'welcome')->name('welcome');;

Route::view('welcome', 'welcome')
    ->middleware(['auth', 'verified'])
    ->name('welcome');
    
// Route::get('/dashboard', [DashboardController::class, 'index'])
//     ->middleware(['web', 'auth'])
//     ->name('dashboard');
// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');

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
