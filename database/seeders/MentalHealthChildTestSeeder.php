<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Result;

class MentalHealthChildTestSeeder extends Seeder
{
    public function run()
    {
        // Tạo bài test
        $test = Test::create([
            'title' => 'Kiểm tra sức khỏe tinh thần cho con',
            'description' => 'Bài kiểm tra này giúp cha mẹ đánh giá xem con có gặp khó khăn về cảm xúc, chú ý hoặc hành vi hay không.',
        ]);

        // Danh sách câu hỏi
        $questions = [
            "Cựa quậy, đứng ngồi không yên",
            "Hành động như thể chạy bằng máy",
            "Mơ mộng quá nhiều",
            "Dễ bị phân tâm",
            "Cảm thấy buồn, không vui",
            "Cảm thấy tuyệt vọng",
            "Kém tập trung",
            "Đánh nhau với những em nhỏ khác",
            "Chán bản thân",
            "Lo lắng nhiều",
            "Có vẻ kém vui",
            "Không nghe các nội quy",
            "Không hiểu cảm xúc của người khác",
            "Chọc ghẹo người khác",
            "Đổ lỗi cho người khác",
            "Lấy đồ của người khác",
            "Không chịu chia sẻ"
        ];

        // Các lựa chọn câu trả lời với điểm số tương ứng
        $answers = [
            ["Không Bao Giờ", 0],
            ["Thỉnh Thoảng", 1],
            ["Thường Xuyên", 2]
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

        // Thêm mức độ & lời khuyên vào database
        $results = [
            [0, 5, "Bình thường", "Con bạn có sức khỏe tinh thần tốt. Hãy tiếp tục duy trì môi trường tích cực cho con."],
            [6, 10, "Có dấu hiệu nhẹ", "Con có thể gặp một số khó khăn nhỏ. Hãy dành thời gian để trò chuyện với con thường xuyên hơn."],
            [11, 15, "Có dấu hiệu trung bình", "Nên theo dõi thêm và có thể tham khảo ý kiến chuyên gia tâm lý nếu cần."],
            [16, 20, "Có dấu hiệu nghiêm trọng", "Hãy xem xét tìm kiếm sự hỗ trợ từ chuyên gia tâm lý hoặc bác sĩ để giúp con vượt qua những khó khăn."],
            [21, 34, "Khả năng cao gặp vấn đề tâm lý", "Cần gặp bác sĩ chuyên khoa ngay lập tức để được tư vấn và hỗ trợ điều trị."]
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
