<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Page Information')
                    ->components([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, $set) {
                                if ($operation !== 'create') {
                                    return;
                                }
                                $set('slug', \Illuminate\Support\Str::slug($state));
                            }),
                        
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash']),
                        
                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->maxLength(160)
                            ->helperText('SEO meta description (max 160 characters)')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Hero Section')
                    ->description('Configure the hero section for this page')
                    ->visible(fn ($get) => !$get('is_contact_page'))
                    ->components([
                        Toggle::make('has_hero')
                            ->label('Enable Hero Section')
                            ->default(false)
                            ->live()
                            ->helperText('Enable hero section for this page'),

                        TextInput::make('hero_title')
                            ->label('Hero Title')
                            ->maxLength(255)
                            ->visible(fn ($get) => $get('has_hero')),

                        Textarea::make('hero_subtitle')
                            ->label('Hero Subtitle')
                            ->maxLength(500)
                            ->visible(fn ($get) => $get('has_hero')),

                        FileUpload::make('hero_video_url')
                            ->label('Hero Video')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg', 'video/avi', 'video/mov'])
                            ->disk('public')
                            ->directory('videos/hero')
                            ->visibility('public')
                            ->maxSize(100 * 1024) // 100MB max
                            ->helperText('Upload a video file (MP4, WebM, OGG, AVI, MOV). Max size: 100MB')
                            ->visible(fn ($get) => $get('has_hero'))
                            ->columnSpanFull(),

                        TextInput::make('hero_button_text')
                            ->label('Button Text')
                            ->maxLength(100)
                            ->visible(fn ($get) => $get('has_hero')),

                        TextInput::make('hero_button_link')
                            ->label('Button Link')
                            ->maxLength(255)
                            ->helperText('Enter the link for the hero button (e.g., #projects, /contact)')
                            ->visible(fn ($get) => $get('has_hero')),
                    ])->columns(2),

                Section::make('Contact Information')
                    ->description('Configure contact information for this page')
                    ->components([
                        Toggle::make('is_contact_page')
                            ->label('Enable Contact Information')
                            ->default(false)
                            ->live()
                            ->helperText('Enable contact information management for this page'),

                        TextInput::make('contact_phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(20)
                            ->visible(fn ($get) => $get('is_contact_page')),

                        TextInput::make('contact_email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(255)
                            ->visible(fn ($get) => $get('is_contact_page')),

                        Textarea::make('contact_address')
                            ->label('Address')
                            ->maxLength(500)
                            ->visible(fn ($get) => $get('is_contact_page'))
                            ->columnSpanFull(),

                        TextInput::make('contact_instagram')
                            ->label('Instagram URL')
                            ->url()
                            ->maxLength(255)
                            ->visible(fn ($get) => $get('is_contact_page')),

                        TextInput::make('contact_whatsapp')
                            ->label('WhatsApp Number')
                            ->tel()
                            ->maxLength(20)
                            ->helperText('Include country code (e.g., 966555453228)')
                            ->visible(fn ($get) => $get('is_contact_page')),

                        TextInput::make('contact_tiktok')
                            ->label('TikTok URL')
                            ->url()
                            ->maxLength(255)
                            ->visible(fn ($get) => $get('is_contact_page')),

                        TextInput::make('contact_youtube')
                            ->label('YouTube URL')
                            ->url()
                            ->maxLength(255)
                            ->visible(fn ($get) => $get('is_contact_page')),

                        Textarea::make('contact_map_embed')
                            ->label('Google Maps Embed Code')
                            ->helperText('Paste the Google Maps embed iframe src URL')
                            ->visible(fn ($get) => $get('is_contact_page'))
                            ->columnSpanFull(),

                        TextInput::make('contact_button_text')
                            ->label('Contact Button Text')
                            ->maxLength(100)
                            ->helperText('Text for the contact call-to-action button')
                            ->visible(fn ($get) => $get('is_contact_page')),

                        TextInput::make('contact_button_link')
                            ->label('Contact Button Link')
                            ->maxLength(255)
                            ->helperText('Link for the contact button (e.g., #contact-form, /services)')
                            ->visible(fn ($get) => $get('is_contact_page')),
                    ])->columns(2),

                Section::make('Page Content')
                    ->components([
                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),
                    ]),

                Section::make('Publishing')
                    ->components([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(false)
                            ->helperText('Only published pages will be visible on the website'),
                    ]),
            ]);
    }
}
