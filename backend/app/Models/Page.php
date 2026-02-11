<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'meta_description',
        'is_published',
        'hero_title',
        'hero_subtitle',
        'hero_video_url',
        'hero_button_text',
        'hero_button_link',
        'has_hero',
        'contact_phone',
        'contact_email',
        'contact_address',
        'contact_instagram',
        'contact_whatsapp',
        'contact_tiktok',
        'contact_youtube',
        'contact_map_embed',
        'contact_button_text',
        'contact_button_link',
        'is_contact_page',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'has_hero' => 'boolean',
        'is_contact_page' => 'boolean',
    ];

    protected static function booted()
    {
        static::updating(function ($page) {
            $page->createVersion();
        });

        static::created(function ($page) {
            $page->createVersion();
        });
    }

    public function versions(): HasMany
    {
        return $this->hasMany(PageVersion::class)->orderBy('version_number', 'desc');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    
    public function createVersion(): void
    {
        $nextVersionNumber = $this->versions()->max('version_number') + 1;

        $this->versions()->create([
            'version_number' => $nextVersionNumber,
            'title' => $this->title,
            'content' => $this->content,
            'meta_description' => $this->meta_description,
            'created_by' => auth()->user()?->name ?? 'System',
        ]);
    }

    
    public function restoreToVersion(int $versionNumber): bool
    {
        $version = $this->versions()->where('version_number', $versionNumber)->first();

        if (!$version) {
            return false;
        }

        $this->update([
            'title' => $version->title,
            'content' => $version->content,
            'meta_description' => $version->meta_description,
        ]);

        return true;
    }

    
    public function getCurrentVersionNumber(): int
    {
        return $this->versions()->max('version_number') ?? 0;
    }

    
    public function getVersionHistory(): array
    {
        return $this->versions()->get()->map(function ($version) {
            return [
                'version_number' => $version->version_number,
                'title' => $version->title,
                'created_at' => $version->created_at,
                'created_by' => $version->created_by,
                'differences' => $version->getDifferences(),
            ];
        })->toArray();
    }

    
    public function getHeroVideoUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (str_starts_with($value, 'http') || str_starts_with($value, '/')) {
            return $value;
        }

        return asset('storage/' . $value);
    }
}
