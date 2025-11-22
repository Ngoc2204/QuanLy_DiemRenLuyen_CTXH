<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interests = [
            [
                'InterestName' => 'Thể thao',
                'Description' => 'Các hoạt động thể chất, thể thao ngoài trời',
                'Icon' => 'fas fa-futbol'
            ],
            [
                'InterestName' => 'Âm nhạc',
                'Description' => 'Âm nhạc, hát, nhạc cụ',
                'Icon' => 'fas fa-music'
            ],
            [
                'InterestName' => 'Nghệ thuật',
                'Description' => 'Vẽ, điêu khắc, thiết kế đồ họa',
                'Icon' => 'fas fa-palette'
            ],
            [
                'InterestName' => 'Tình nguyện',
                'Description' => 'Hoạt động từ thiện, tình nguyện xã hội',
                'Icon' => 'fas fa-hands-helping'
            ],
            [
                'InterestName' => 'Khoa học & Công nghệ',
                'Description' => 'STEM, lập trình, khoa học',
                'Icon' => 'fas fa-flask'
            ],
            [
                'InterestName' => 'Ngoại ngữ',
                'Description' => 'Học ngoại ngữ, trao đổi văn hóa',
                'Icon' => 'fas fa-language'
            ],
            [
                'InterestName' => 'Kinh doanh',
                'Description' => 'Kiến thức kinh doanh, startup',
                'Icon' => 'fas fa-briefcase'
            ],
            [
                'InterestName' => 'Giáo dục',
                'Description' => 'Dạy học, hỗ trợ giáo dục',
                'Icon' => 'fas fa-book'
            ],
            [
                'InterestName' => 'Du lịch',
                'Description' => 'Du lịch, khám phá địa điểm',
                'Icon' => 'fas fa-map-marked-alt'
            ],
            [
                'InterestName' => 'Nấu ăn',
                'Description' => 'Nấu ăn, ẩm thực, nông nghiệp',
                'Icon' => 'fas fa-utensils'
            ],
        ];

        DB::table('interests')->insert($interests);
    }
}
