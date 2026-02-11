<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactPageSeeder extends Seeder
{
    
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'contact'],
            [
                'title' => 'اتصل بنا',
                'content' => '<p>تواصل معنا للحصول على استشارة مجانية</p>',
                'meta_description' => 'تواصل مع Future Homes - معلومات الاتصال والموقع',
                'is_published' => true,
                'is_contact_page' => true,
                'contact_phone' => '+966 55 545 3228',
                'contact_email' => 'sales@fuchomes.com',
                'contact_address' => 'المملكة العربية السعودية - الرياض- شارع عثمان بن عفان - التعاون',
                'contact_instagram' => 'https://www.instagram.com/futurehomes777',
                'contact_whatsapp' => '966555453228',
                'contact_tiktok' => '',
                'contact_youtube' => '',
                'contact_map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3623.504953931109!2d46.70295257545934!3d24.77332304918712!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2efd26a27e0fd3%3A0xc0233e145b410403!2z2YPZhNis2Kcg2KfZhNmD2YTZhdix2YrYqSDYp9mE2YTZhdmK2KkgLdiz2YTZitix!5e0!3m2!1sen!2ssa!4v1663186178873!5m2!1sen!2ssa',
            ]
        );
    }
}
