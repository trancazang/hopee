<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Result;

class PHQ9Seeder extends Seeder
{
    public function run()
    {
        // Tạo bài test PHQ-9
        $test = Test::create([
            'title' => 'Bài Test Trầm Cảm PHQ-9',
            'description' => 'Bài test PHQ-9 giúp đánh giá mức độ trầm cảm trong 2 tuần qua.',
        ]);

        // Danh sách câu hỏi PHQ-9
        $questions = [
            "Bạn có cảm thấy ít hứng thú hoặc niềm vui trong các hoạt động hàng ngày không?",
            "Bạn có cảm thấy buồn bã, chán nản hoặc tuyệt vọng không?",
            "Bạn có gặp khó khăn trong việc ngủ (khó ngủ, tỉnh giấc sớm, ngủ quá nhiều)?",
            "Bạn có cảm thấy mệt mỏi hoặc thiếu năng lượng không?",
            "Bạn có cảm thấy chán ăn hoặc ăn quá nhiều không?",
            "Bạn có cảm thấy bản thân thất bại hoặc có suy nghĩ tồi tệ về bản thân không?",
            "Bạn có gặp khó khăn trong việc tập trung (ví dụ như đọc báo hoặc xem TV)?",
            "Bạn có cảm thấy di chuyển hoặc nói chậm hơn bình thường, hoặc ngược lại, cảm thấy bồn chồn hơn?",
            "Bạn có suy nghĩ về việc tự làm hại bản thân hoặc tự tử không?"
        ];

        // Các lựa chọn câu trả lời với điểm số tương ứng
        $answers = [
            ["Chưa bao giờ", 0],
            ["Một vài ngày", 1],
            ["Hơn một nửa số ngày", 2],
            ["Gần như mỗi ngày", 3]
        ];

        // Thêm câu hỏi & đáp án vào database
        foreach ($questions as $q) {
            $question = Question::create([
                'test_id' => $test->id,
                'question' => $q,
            ]);

            foreach ($answers as $answer) {
                Answer::create([
                    'question_id' => $question->id,
                    'answer' => $answer[0],
                    'score' => $answer[1],
                ]);
            }
        }

        // Thêm mức độ trầm cảm & lời khuyên vào database
        $results = [
            [0, 4, "Không có dấu hiệu trầm cảm", "Bạn không có dấu hiệu trầm cảm. Hãy duy trì lối sống tích cực."],
            [5, 9, "Trầm cảm nhẹ", "Hãy thử các biện pháp tự quản lý như thiền, tập thể dục và ngủ đủ giấc."],
            [10, 14, "Trầm cảm trung bình", "Nên tham khảo ý kiến chuyên gia tâm lý để được hướng dẫn thêm."],
            [15, 19, "Trầm cảm nặng (trung bình)", "Cần xem xét điều trị tâm lý hoặc điều trị bằng thuốc theo hướng dẫn bác sĩ."],
            [20, 27, "Trầm cảm nặng (nặng)", "Cần gặp bác sĩ ngay lập tức để được hỗ trợ chuyên sâu."]
        ];

        foreach ($results as $res) {
            Result::create([
                'test_id' => $test->id,
                'min_score' => $res[0],
                'max_score' => $res[1],
                'level' => $res[2],
                'description' => $res[3],
                'advice' => $res[3],
            ]);
        }
    }
}