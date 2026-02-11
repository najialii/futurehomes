<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\Service;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project Information')
                    ->components([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        
                        Select::make('service_id')
                            ->label('Service Category')
                            ->options(Service::active()->pluck('title', 'id'))
                            ->required()
                            ->searchable(),
                        
                        Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Project Settings')
                    ->components([
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->required(),
                        
                        TextInput::make('display_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Toggle::make('is_featured')
                            ->label('Featured on Homepage')
                            ->helperText('Show this project on the homepage featured section')
                            ->default(false),
                    ])->columns(3),

                Section::make('Project Images')
                    ->components([
                        Repeater::make('images')
                            ->label('Project Images')
                            ->relationship()
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Image')
                                    ->image()
                                    ->directory('projects')
                                    ->visibility('public')
                                    ->maxSize(51200) // 50MB
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->required(),
                                
                                TextInput::make('alt_text')
                                    ->label('Alt Text')
                                    ->maxLength(255),
                                
                                TextInput::make('display_order')
                                    ->label('Display Order')
                                    ->numeric()
                                    ->default(1),
                            ])
                            ->columns(3)
                            ->reorderable('display_order')
                            ->columnSpanFull()
                            ->helperText('Upload multiple images for this project. You can drag to reorder them.'),
                    ]),
            ]);
    }
}
