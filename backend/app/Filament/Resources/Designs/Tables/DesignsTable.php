<?php

namespace App\Filament\Resources\Designs\Tables;

use App\Models\Design;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DesignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('الصورة')
                    ->height(60)
                    ->width(60),

                TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                BadgeColumn::make('category')
                    ->label('الفئة')
                    ->formatStateUsing(fn (string $state): string => Design::getCategories()[$state] ?? $state)
                    ->colors([
                        'primary' => 'interior',
                        'success' => 'exterior',
                        'warning' => 'landscape',
                        'info' => 'architectural',
                        'secondary' => 'general',
                    ]),

                BadgeColumn::make('status')
                    ->label('الحالة')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'مسودة',
                        'published' => 'منشور',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                    ]),

                IconColumn::make('is_featured')
                    ->label('مميز')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('tags')
                    ->label('العلامات')
                    ->formatStateUsing(function ($state) {
                        if (!$state || !is_array($state)) {
                            return '-';
                        }
                        $tagLabels = Design::getAvailableTags();
                        return collect($state)
                            ->map(fn ($tag) => $tagLabels[$tag] ?? $tag)
                            ->take(3)
                            ->join('، ') . (count($state) > 3 ? '...' : '');
                    })
                    ->limit(50),

                TextColumn::make('display_order')
                    ->label('الترتيب')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('الفئة')
                    ->options(Design::getCategories()),

                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'draft' => 'مسودة',
                        'published' => 'منشور',
                    ]),

                TernaryFilter::make('is_featured')
                    ->label('مميز')
                    ->placeholder('الكل')
                    ->trueLabel('مميز فقط')
                    ->falseLabel('غير مميز فقط'),
            ])
            ->actions([
                ViewAction::make()
                    ->label('عرض'),
                EditAction::make()
                    ->label('تعديل'),
                DeleteAction::make()
                    ->label('حذف'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('حذف المحدد'),
                ]),
            ])
            ->defaultSort('display_order');
    }
}