<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Client Information')
                    ->components([
                        TextInput::make('client_name')
                            ->label('Client Name')
                            ->required()
                            ->maxLength(255),
                        
                        FileUpload::make('client_photo_path')
                            ->label('Client Photo')
                            ->image()
                            ->directory('testimonials/photos')
                            ->visibility('public')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Testimonial Content')
                    ->components([
                        Textarea::make('feedback')
                            ->label('Testimonial Text')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        
                        Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '1 Star',
                                2 => '2 Stars',
                                3 => '3 Stars',
                                4 => '4 Stars',
                                5 => '5 Stars',
                            ])
                            ->default(5)
                            ->required(),
                        
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending Review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->default('pending')
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
