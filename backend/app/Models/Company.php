<?php

namespace App\Models;

use App\Rules\PhoneNumberRule;
use App\Services\HtmlSanitizationService;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Company extends Model implements HasMedia
{
    use InteractsWithMedia, Auditable;

    protected $fillable = [
        'name',
        'description',
        'email',
        'phone',
        'address',
        'logo_path',
        'website_url',
        'social_media',
    ];

    protected $casts = [
        'social_media' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/svg+xml']);
    }

    
    public static function getValidationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', new PhoneNumberRule()],
            'address' => ['nullable', 'string', 'max:500'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'social_media' => ['nullable', 'array'],
            'social_media.*' => ['url', 'max:255'],
        ];
    }

    
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $sanitizer = new HtmlSanitizationService();
            
            if ($model->name) {
                $model->name = $sanitizer->sanitizeBasicText($model->name);
            }
            
            if ($model->description) {
                $model->description = $sanitizer->sanitizeRichText($model->description);
            }
            
            if ($model->website_url) {
                $model->website_url = $sanitizer->sanitizeUrl($model->website_url);
            }
        });
    }
}
