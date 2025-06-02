<?php

namespace App\Services;

use App\Models\AdviceRequest;
use App\Mail\AdviceScheduledMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Musonza\Chat\Facades\ChatFacade as Chat;


class AdviceRequestNotifier
{
    
    /**
     * Gửi email và tin nhắn chat khi moderator đã tiếp nhận yêu cầu tư vấn.
     *
     * @param AdviceRequest $request
     */
    /**
     * Gửi email và tin nhắn chat khi moderator đã tiếp nhận yêu cầu tư vấn.
     *
     * @param  AdviceRequest  $request
     * @return void
     */
    public function notify(AdviceRequest $request): void
    {
        $user      = $request->user;
        $moderator = $request->moderator;

        if (! $user || ! $moderator) {
            Log::warning("AdviceRequestNotifier: Thiếu user hoặc moderator. Request ID={$request->id}");
            return;
        }

        // --------------------------------
        // 1. GỬI EMAIL XÁC NHẬN QUA SMTP
        // --------------------------------
        try {
            Log::info("AdviceRequestNotifier: Đang gửi email đến {$user->email}");
            Mail::to($user->email)->send(new AdviceScheduledMail($request));
            Log::info("AdviceRequestNotifier: Email đã gửi thành công đến {$user->email}");
        } catch (\Throwable $e) {
            Log::error("AdviceRequestNotifier: Lỗi khi gửi email: {$e->getMessage()}");
        }

        // ------------------------------------------
        // 2. GỬI TIN NHẮN QUA Musonza Chat
        // ------------------------------------------
        try {
            Log::info("AdviceRequestNotifier: Tạo/lay Conversation giữa moderator_id={$moderator->id} và user_id={$user->id}");

            // Tạo conversation từ 2 model User: $moderator và $user
            $conversation = Chat::createConversation([$moderator, $user]);

            if (! $conversation) {
                Log::warning("AdviceRequestNotifier: Tạo conversation thất bại cho mod={$moderator->id}, user={$user->id}");
                return;
            }

            Log::info("AdviceRequestNotifier: Đã có Conversation ID={$conversation->id}");

            // Lấy link cuộc họp (nếu có)
            $meetingLink = $request->meeting_link;

            // Định dạng ngày giờ hiển thị
            $scheduledAtText = \Carbon\Carbon::parse($request->scheduled_at)
                ->format('H:i d/m/Y');

            // Tạo nội dung tin nhắn chat với link (nếu có)
            $messageText = "👋 Chào bạn {$user->name},\n";
            $messageText .= "Yêu cầu tư vấn của bạn đã được tiếp nhận và lên lịch vào lúc **{$scheduledAtText}**.\n";

            if ($meetingLink) {
                $messageText .= "🖥 Link cuộc họp: {$meetingLink}\n";
            }

            $messageText .= "Chúng tôi sẽ liên hệ trước khi bắt đầu buổi tư vấn. Chúc bạn một buổi làm việc hiệu quả!";

            Chat::message($messageText)
                ->from($moderator)
                ->to($conversation)
                ->send();

            Log::info("AdviceRequestNotifier: Tin nhắn chat đã gửi thành công vào Conversation ID={$conversation->id}");
        } catch (\Throwable $e) {
            Log::error("AdviceRequestNotifier: Lỗi khi gửi chat message: {$e->getMessage()}");
        }
    }
}
