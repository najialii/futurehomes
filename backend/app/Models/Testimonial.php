<?php

namespace App\Models;

use App\Rules\RatingRule;
use App\Services\HtmlSanitizationService;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Testimonial extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'client_name',
        'client_photo_path',
        'feedback',
        'rating',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('client_photo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png']);
    }

    public function getClientPhotoUrlAttribute(): ?string
    {
        return $this->client_photo_path ? asset('storage/' . $this->client_photo_path) : null;
    }

    /**
     * Get validation rules for testimonial data
     */
    public static function getValidationRules(): array
    {
        return [
            'client_name' => ['required', 'string', 'max:255'],
            'feedback' => ['required', 'string', 'max:1000'],
            'rating' => ['required', new RatingRule()],
            'status' => ['required', 'in:pending,approved,rejected'],
        ];
    }

    /**
     * Sanitize input before saving
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $sanitizer = new HtmlSanitizationService();
            
            if ($model->client_name) {
                $model->client_name = $sanitizer->sanitizeBasicText($model->client_name);
            }
            
            if ($model->feedback) {
                $model->feedback = $sanitizer->sanitizeRichText($model->feedback);
            }
        });
    }
}
