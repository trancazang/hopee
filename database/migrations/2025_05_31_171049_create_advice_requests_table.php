<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('advice_requests', function (Blueprint $table) {
            $table->id();

            // Người gửi yêu cầu
            $table->foreignId('user_id')
                  ->constrained()                  // users.id
                  ->cascadeOnDelete();

            
                
            /* ==== Trạng thái & phiên tư vấn ==== */
            $table->enum('status', [
                    'pending',     // vừa đăng ký, chưa ai nhận
                    'scheduled',   // moderator đã nhận & đặt lịch
                    'completed',   // phiên tư vấn xong
                    'cancelled',   // hủy bởi user/moderator
                    'no-show'      // không đến
                ])->default('pending');

            // Thời gian user mong muốn
            $table->timestamp('preferred_time')->nullable();

            // Moderator phụ trách (null khi chưa ai nhận)
            $table->foreignId('moderator_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Thời gian đã ấn định & link họp (nếu đã scheduled)
            $table->timestamp('scheduled_at')->nullable();
            $table->string('meeting_link')->nullable();

            // Ghi chú nội bộ cho moderator
            $table->text('moderator_notes')->nullable();

            // Đánh dấu đã gửi email nhắc
            $table->timestamp('reminded_at')->nullable();

            $table->text('notes')->nullable();      // ghi chú của user
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advice_requests');
    }
};
