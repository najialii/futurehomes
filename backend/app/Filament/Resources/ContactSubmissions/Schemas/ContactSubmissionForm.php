<?php

namespace App\Filament\Resources\ContactSubmissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;

class ContactSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact Information')
                    ->components([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        
                        Textarea::make('message')
                            ->required()
                            ->rows(4)
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Submission Details')
                    ->components([
                        Select::make('status')
                            ->options([
                                'new' => 'New',
                                'read' => 'Read',
                                'replied' => 'Replied',
                                'archived' => 'Archived',
                            ])
                            ->default('new')
                            ->required(),
                        
                        Placeholder::make('created_at')
                            ->label('Submitted At')
                            ->content(fn ($record) => $record?->created_at?->format('M j, Y g:i A')),
                        
                        TextInput::make('ip_address')
                            ->label('IP Address')
                            ->disabled(),
                        
                        Textarea::make('user_agent')
                            ->label('User Agent')
                            ->disabled()
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
