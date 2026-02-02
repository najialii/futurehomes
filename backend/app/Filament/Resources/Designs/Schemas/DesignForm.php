<?php

namespace App\Filament\Resources\Designs\Schemas;

use App\Models\Design;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DesignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات التصميم الأساسية')
                    ->description('أدخل المعلومات الأساسية للتصميم')
                    ->schema([
                        TextInput::make('title')
                            ->label('عنوان التصميم')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('مثال: تصميم فيلا حديثة'),

                        Select::make('category')
                            ->label('فئة التصميم')
                            ->required()
                            ->options(Design::getCategories())
                            ->default('general'),

                        Textarea::make('description')
                            ->label('وصف التصميم')
                            ->rows(3)
                            ->placeholder('وصف مفصل للتصميم وخصائصه...')
                            ->columnSpanFull(),

                        TextInput::make('alt_text')
                            ->label('النص البديل للصورة')
                            ->maxLength(255)
                            ->placeholder('وصف الصورة للمساعدة في إمكانية الوصول')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('صورة التصميم')
                    ->description('ارفع صورة التصميم')
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('صورة التصميم')
                            ->required()
                            ->image()
                            ->directory('designs')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(5120) // 5MB
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('الحد الأقصى لحجم الملف: 5 ميجابايت. الصيغ المدعومة: JPEG, PNG, WebP')
                            ->columnSpanFull(),
                    ]),

                Section::make('إعدادات التصميم')
                    ->description('إعدادات العرض والتصنيف')
                    ->schema([
                        Select::make('status')
                            ->label('حالة النشر')
                            ->required()
                            ->options([
                                'draft' => 'مسودة',
                                'published' => 'منشور',
                            ])
                            ->default('published'),

                        Toggle::make('is_featured')
                            ->label('تصميم مميز')
                            ->helperText('سيظهر في قسم التصاميم المميزة'),

                        TextInput::make('display_order')
                            ->label('ترتيب العرض')
                            ->numeric()
                            ->default(0)
                            ->helperText('الرقم الأصغر يظهر أولاً'),

                        TagsInput::make('tags')
                            ->label('علامات التصميم')
                            ->suggestions(array_keys(Design::getAvailableTags()))
                            ->helperText('أضف علامات لتسهيل البحث والتصنيف (مثل: حديث، كلاسيكي، فاخر)')
                            ->placeholder('اكتب علامة واضغط Enter')
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }
}