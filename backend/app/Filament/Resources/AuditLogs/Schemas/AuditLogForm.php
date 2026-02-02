<?php

namespace App\Filament\Resources\AuditLogs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;

class AuditLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Audit Information')
                    ->components([
                        TextInput::make('auditable_type')
                            ->label('Model Type')
                            ->disabled(),
                        
                        TextInput::make('auditable_id')
                            ->label('Model ID')
                            ->disabled(),
                        
                        TextInput::make('event')
                            ->label('Event Type')
                            ->disabled(),
                        
                        TextInput::make('user_id')
                            ->label('User ID')
                            ->disabled(),
                    ])->columns(2),

                Section::make('Changes')
                    ->components([
                        Textarea::make('old_values')
                            ->label('Old Values')
                            ->disabled()
                            ->rows(4)
                            ->columnSpanFull(),
                        
                        Textarea::make('new_values')
                            ->label('New Values')
                            ->disabled()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Metadata')
                    ->components([
                        TextInput::make('ip_address')
                            ->label('IP Address')
                            ->disabled(),
                        
                        Placeholder::make('created_at')
                            ->label('Created At')
                            ->content(fn ($record) => $record?->created_at?->format('M j, Y g:i A')),
                        
                        Textarea::make('user_agent')
                            ->label('User Agent')
                            ->disabled()
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
