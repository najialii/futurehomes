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
    ];

    protected $casts = [
        'is_published' => 'boolean',
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

    /**
     * Create a new version of this page
     */
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

    /**
     * Restore to a specific version
     */
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

    /**
     * Get the current version number
     */
    public function getCurrentVersionNumber(): int
    {
        return $this->versions()->max('version_number') ?? 0;
    }

    /**
     * Get version history with differences
     */
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
}
