<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AdminTestController;
use App\Livewire\Actions\Logout;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForumSearchController;
use App\Http\Controllers\ForumController;
use App\Livewire\{
    AdviceRequestForm, ScheduleManager, AdviceManageComponent, AdviceRateComponent, AdviceHistoryComponent, AdviceModeratorCalendar
};
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CkeditorController;

Route::middleware(['auth'])->group(function () {
    // người dùng đăng ký
    Route::get('/advice/request', AdviceRequestForm::class)
         ->name('advice.request');


Route::middleware(['auth'])->group(function () {
    // Trang quản lý (Manage + Modal Schedule nested)
    Route::get('/advice/manage', AdviceManageComponent::class)
         ->name('advice.manage');
});

         

    // moderator nhận & đặt lịch
    Route::get('/advice/schedule', ScheduleManager::class)
         ->name('advice.schedule');
    


    // người dùng đánh giá sau phiên
    Route::get('/advice/{advice}/rate', AdviceRateComponent::class)
         ->name('advice.rate');
    // người dùng xem lịch sử
    Route::get('/advice/history', AdviceHistoryComponent::class)
        ->name('advice.history');
});
    // moderator lên lịch
    Route::get('/advice/calendar', AdviceModeratorCalendar::class)
    ->name('advice.calendar');



Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/vote', [ForumController::class, 'vote'])->name('posts.vote');
    Route::post('/posts/{post}/report', [ForumController::class, 'report'])->name('posts.report');
});


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
// routes/web.php
Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::post('/chatbot/setup', [ChatbotController::class, 'setup'])->name('chatbot.setup');
require __DIR__.'/auth.php';
Route::post('/upload-image', function (Request $request) {
    if ($request->hasFile('upload')) {
        $path = $request->file('upload')->store('uploads', 'public');
        return response()->json([
            'url' => asset('storage/' . $path)
        ]);
    }
    return response()->json(['error' => 'No file uploaded'], 400);
});

Route::post('/ckeditor/upload', [CkeditorController::class, 'upload'])
     ->name('ckeditor.upload');