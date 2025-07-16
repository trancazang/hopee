<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Result;

class PSS10Seeder extends Seeder
{
    public function run()
    {
        /*-----------------------------------------
        | 1. Tạo bài test PSS‑10
        |-----------------------------------------*/
        $test = Test::create([
            'title'       => 'Bài Test Căng Thẳng PSS‑10',
            'description' => 'Thang đo Căng thẳng Cảm nhận (PSS‑10) đánh giá mức độ căng thẳng bạn cảm nhận trong 1 tháng qua.',
        ]);

        /*-----------------------------------------
        | 2. 10 câu hỏi PSS‑10
        |-----------------------------------------*/
        $questions = [
            "Trong tháng vừa qua, bạn cảm thấy buồn bực vì điều gì đó xảy ra bất ngờ đến mức bạn thấy mất kiểm soát không?",
            "Trong tháng vừa qua, bạn cảm thấy mình không thể kiểm soát những điều quan trọng trong cuộc sống của mình không?",
            "Trong tháng vừa qua, bạn cảm thấy lo lắng và căng thẳng không?",
            "Trong tháng vừa qua, bạn cảm thấy bạn có thể đối mặt hiệu quả với các vấn đề cá nhân không?",          // đảo điểm
            "Trong tháng vừa qua, bạn cảm thấy rằng mọi việc đang diễn ra theo ý bạn không?",                         // đảo điểm
            "Trong tháng vừa qua, bạn cảm thấy quá tải đến mức không thể vượt qua những gì bạn phải làm không?",
            "Trong tháng vừa qua, bạn cảm thấy có thể kiểm soát được những khó khăn trong cuộc sống không?",          // đảo điểm
            "Trong tháng vừa qua, bạn cảm thấy tức giận vì những điều không nằm trong tầm kiểm soát của bạn không?",
            "Trong tháng vừa qua, bạn cảm thấy mọi việc đang diễn ra không như ý bạn không?",
            "Trong tháng vừa qua, bạn cảm thấy rằng khó khăn đang chồng chất đến mức bạn không thể vượt qua được không?",
        ];

        /*-----------------------------------------
        | 3. Thang trả lời & điểm
        |-----------------------------------------*/
        $answers = [
            ["Không bao giờ",       0],
            ["Hiếm khi",            1],
            ["Thỉnh thoảng",        2],
            ["Thường xuyên",        3],
            ["Rất thường xuyên",    4],
        ];

        /*-----------------------------------------
        | 4. Lưu câu hỏi + đáp án
        |-----------------------------------------*/
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

        /*-----------------------------------------
        | 5. Kết quả & lời khuyên
        |-----------------------------------------*/
        $results = [
            [0, 13,  "Thấp",      "Bạn đang có mức căng thẳng thấp. Hãy duy trì lối sống cân bằng và các hoạt động thư giãn."],
            [14, 26, "Vừa phải",  "Mức căng thẳng trung bình. Nên áp dụng kỹ thuật quản lý stress (thiền, hít thở sâu, thể dục, nghỉ ngơi hợp lý)."],
            [27, 40, "Cao",       "Mức căng thẳng cao. Hãy tìm kiếm hỗ trợ chuyên môn (chuyên gia tâm lý hoặc bác sĩ) để được tư vấn chuyên sâu."],
        ];

        foreach ($results as $res) {
            Result::create([
                'test_id'    => $test->id,
                'min_score'  => $res[0],
                'max_score'  => $res[1],
                'level'      => $res[2],
                'description'=> "Mức căng thẳng " . strtolower($res[2]),
                'advice'     => $res[3],
            ]);
        }
    }
}
