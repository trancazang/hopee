<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForumCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Sức khỏe tinh thần',
                'description' => 'Thảo luận về trầm cảm, lo âu, stress và cách vượt qua.',
                'accepts_threads' => 1,
                'is_private' => 0,
                'color_light_mode' => '#ff6f61',
                'color_dark_mode' => '#b23c3a',
            ],
            [
                'title' => 'Tư duy tích cực',
                'description' => 'Chia sẻ về lòng biết ơn, lối sống tích cực và truyền động lực.',
                'accepts_threads' => 1,
                'is_private' => 0,
                'color_light_mode' => '#6fcf97',
                'color_dark_mode' => '#27ae60',
            ],
            [
                'title' => 'Tâm lý học tuổi trẻ',
                'description' => 'Nơi chia sẻ những vấn đề tuổi teen, tự tin, dậy thì, mối quan hệ.',
                'accepts_threads' => 1,
                'is_private' => 0,
                'color_light_mode' => '#56ccf2',
                'color_dark_mode' => '#2f80ed',
            ],
            [
                'title' => 'Thảo luận riêng tư',
                'description' => 'Khu vực tâm sự kín dành cho người dùng được phân quyền.',
                'accepts_threads' => 1,
                'is_private' => 1,
                'color_light_mode' => '#bbbbbb',
                'color_dark_mode' => '#333333',
            ],
            [
                'title' => 'Tư vấn tình cảm',
                'description' => 'Thảo luận về tình yêu, tình bạn, gia đình và các mối quan hệ xã hội.',
                'accepts_threads' => 1,
                'is_private' => 0,
                'color_light_mode' => '#f9ca24',
                'color_dark_mode' => '#f39c12',
            ],
        ];

        foreach ($categories as $cat) {
            DB::table('forum_categories')->insert([
                'title' => $cat['title'],
                'description' => $cat['description'],
                'accepts_threads' => $cat['accepts_threads'],
                'is_private' => $cat['is_private'],
                'color_light_mode' => $cat['color_light_mode'],
                'color_dark_mode' => $cat['color_dark_mode'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
