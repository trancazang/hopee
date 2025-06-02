@component('mail::message')
# Yêu cầu tư vấn của bạn đã được tiếp nhận

Xin chào **{{ $user->name }}**,

Chuyên gia tư vấn đã tiếp nhận yêu cầu của bạn và lên lịch hẹn như sau:

- ⏰ **Thời gian**: {{ \Carbon\Carbon::parse($scheduled_at)->format('H:i d/m/Y') }}
@isset($meeting_link)
- 🔗 **Link cuộc hẹn**: [Tham gia tại đây]({{ $meeting_link }})
@endisset

Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ lại với chúng tôi.

@component('mail::button', ['url' => url('/advice/history')])
Xem lịch sử tư vấn
@endcomponent

Cảm ơn bạn đã tin tưởng SheZen!

@endcomponent
