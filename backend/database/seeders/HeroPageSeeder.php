<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeroPageSeeder extends Seeder
{
    
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'الصفحة الرئيسية',
                'content' => '<p>مرحباً بكم في موقع Future Homes</p>',
                'meta_description' => 'Future Homes - نضع خبرة تزيد عن 15 عاماً بين يديك',
                'is_published' => true,
                'has_hero' => true,
                'hero_title' => 'نضع خبرة تزيد عن 15 عاماً بين يديك',
                'hero_subtitle' => 'من التصميم إلى التشطيب، ننفذ مشاريعك باحترافية تامة.',
                'hero_video_url' => '/Promo (1).mp4',
                'hero_button_text' => 'اكتشف مشاريعنا',
                'hero_button_link' => '#projects',
            ]
        );
    }
}
