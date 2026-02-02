<?php

namespace App\Filament\Resources\AuditLogs\Schemas;

use Filament\Schemas\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AuditLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Audit Information')
                    ->schema([
                        TextEntry::make('auditable_type')
                            ->label('Model Type'),
                        
                        TextEntry::make('auditable_id')
                            ->label('Model ID'),
                        
                        TextEntry::make('event')
                            ->label('Event Type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'created' => 'success',
                                'updated' => 'warning',
                                'deleted' => 'danger',
                                default => 'gray',
                            }),
                        
                        TextEntry::make('user.name')
                            ->label('User')
                            ->default('System'),
                    ])->columns(2),

                Section::make('Changes')
                    ->schema([
                        TextEntry::make('old_values')
                            ->label('Old Values')
                            ->formatStateUsing(fn ($state) => $state ? json_encode($state, JSON_PRETTY_PRINT) : 'N/A')
                            ->columnSpanFull(),
                        
                        TextEntry::make('new_values')
                            ->label('New Values')
                            ->formatStateUsing(fn ($state) => $state ? json_encode($state, JSON_PRETTY_PRINT) : 'N/A')
                            ->columnSpanFull(),
                    ]),

                Section::make('Metadata')
                    ->schema([
                        TextEntry::make('ip_address')
                            ->label('IP Address'),
                        
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                        
                        TextEntry::make('user_agent')
                            ->label('User Agent')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
