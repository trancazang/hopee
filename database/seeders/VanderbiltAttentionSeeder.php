<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Result;

class VanderbiltAttentionSeeder extends Seeder
{
    public function run()
    {
        /* 1. Tạo bài test */
        $test = Test::create([
            'title' => 'Bài Test Mức Độ Chú Ý Trẻ (Vanderbilt rút gọn)',
            'description' => 'Thang 18 câu giúp sàng lọc biểu hiện thiếu chú ý và hiếu động/xung động ở trẻ trong 6 tháng qua.',
        ]);

        /* 2. 18 câu hỏi */
        $questions = [
            /* 1‑9: Inattention */
            'Thường không chú ý tới chi tiết, mắc lỗi cẩu thả trong bài/việc.',
            'Khó duy trì sự chú ý khi làm việc hoặc chơi.',
            'Có vẻ không nghe khi được nói trực tiếp.',
            'Không hoàn thành công việc hoặc bài tập (không phải do cãi lệnh).',
            'Khó tổ chức công việc hoặc hoạt động.',
            'Tránh/ghét việc cần nỗ lực trí óc kéo dài.',
            'Làm mất đồ cần cho hoạt động (bút, sách…).',
            'Dễ bị phân tâm bởi kích thích bên ngoài.',
            'Thường quên sinh hoạt hằng ngày.',
            /* 10‑18: Hyperactivity/Impulsivity */
            'Ngọ nguậy tay chân, lắc lư ghế.',
            'Rời khỏi chỗ khi cần ngồi yên.',
            'Chạy/leo trèo quá mức (hoặc cảm thấy bồn chồn ở thiếu niên).',
            'Khó chơi hoặc hoạt động yên tĩnh.',
            'Luôn “đang di chuyển” như “bị gắn mô‑tơ”.',
            'Nói quá nhiều.',
            'Trả lời chóng vánh trước khi nghe hết câu hỏi.',
            'Gặp khó khăn chờ đến lượt mình.',
            'Ngắt lời hoặc chen ngang người khác.',
        ];

        /* 3. Thang trả lời 0‑3 */
        $answers = [
            ['Không bao giờ',     0],
            ['Thỉnh thoảng',      1],
            ['Thường xuyên',      2],
            ['Rất thường xuyên',  3],
        ];

        foreach ($questions as $q) {
            $question = Question::create([
                'test_id'  => $test->id,
                'question' => $q,
            ]);

            foreach ($answers as $ans) {
                Answer::create([
                    'question_id' => $question->id,
                    'answer'      => $ans[0],
                    'score'       => $ans[1],
                ]);
            }
        }

        /* 4. Kết quả đơn giản (tổng điểm) – có thể tùy chỉnh chi tiết hơn */
        $results = [
            [0, 17,  'Không đáng lo', 'Chưa có đủ dấu hiệu cần can thiệp. Tiếp tục quan sát và hỗ trợ trẻ hình thành thói quen học tập tốt.'],
            [18, 35, 'Cần theo dõi', 'Trẻ có biểu hiện chú ý kém hoặc hiếu động nhẹ. Cha mẹ nên áp dụng kỹ thuật quản lý hành vi, tăng thời gian hoạt động thể chất và trao đổi với giáo viên.'],
            [36, 54, 'Nghi ngờ ADHD', 'Điểm số cao, khuyến nghị phụ huynh đưa trẻ đến chuyên gia tâm lý hoặc bác sĩ nhi khoa phát triển để đánh giá toàn diện.'],
        ];

        foreach ($results as $res) {
            Result::create([
                'test_id'    => $test->id,
                'min_score'  => $res[0],
                'max_score'  => $res[1],
                'level'      => $res[2],
                'description'=> $res[2],
                'advice'     => $res[3],
            ]);
        }
    }
}
