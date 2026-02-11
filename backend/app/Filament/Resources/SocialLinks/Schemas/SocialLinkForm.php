<?php

namespace App\Filament\Resources\SocialLinks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SocialLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Display name for the social media platform (e.g., Instagram, WhatsApp)'),

                Select::make('icon')
                    ->required()
                    ->options([
                        'instagram' => 'Instagram',
                        'whatsapp' => 'WhatsApp',
                        'tiktok' => 'TikTok',
                        'youtube' => 'YouTube',
                        'snapchat' => 'Snapchat',
                        'facebook' => 'Facebook',
                        'twitter' => 'Twitter (X)',
                        'linkedin' => 'LinkedIn',
                        'pinterest' => 'Pinterest',
                        'telegram' => 'Telegram',
                    ])
                    ->searchable()
                    ->helperText('Select the FontAwesome icon for this social media platform'),

                TextInput::make('url')
                    ->required()
                    ->url()
                    ->maxLength(255)
                    ->helperText('Full URL to your social media profile (e.g., https://instagram.com/yourprofile)'),

                TextInput::make('display_order')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->helperText('Order in which this link appears (lower numbers appear first)'),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Only active links will be displayed on the website'),
            ]);
    }
}
