<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SocialLink;

class SocialLinksSeeder extends Seeder
{
    public function run(): void
    {
        $socialLinks = [
            [
                'name' => 'Instagram',
                'url' => 'https://www.instagram.com/future_homes.1?igsh=cm5haDk1enZydWRr',
                'icon' => 'instagram',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'WhatsApp',
                'url' => 'https://wa.me/966590007681',
                'icon' => 'whatsapp',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'TikTok',
                'url' => 'https://www.tiktok.com/@future..homes?is_from_webapp=1&sender_device=pc',
                'icon' => 'tiktok',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Snapchat',
                'url' => 'https://www.snapchat.com/add/fuc.homes?share_id=KiN_79l5vFA&locale=en-US',
                'icon' => 'snapchat',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'YouTube',
                'url' => '',
                'icon' => 'youtube',
                'display_order' => 5,
                'is_active' => false,
            ],
        ];

        foreach ($socialLinks as $link) {
            SocialLink::updateOrCreate(
                ['name' => $link['name']],
                $link
            );
        }
    }
}
