<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Design extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'title',
        'description',
        'category',
        'image_path',
        'alt_text',
        'display_order',
        'status',
        'is_featured',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return $this->image_path ? url('/images/' . $this->image_path) : null;
    }

    // Available categories
    public static function getCategories()
    {
        return [
            'interior' => 'التصميم الداخلي',
            'exterior' => 'التصميم الخارجي',
            'landscape' => 'تصميم المناظر الطبيعية',
            'architectural' => 'التصميم المعماري',
            'general' => 'عام',
        ];
    }

    // Available tags
    public static function getAvailableTags()
    {
        return [
            'modern' => 'حديث',
            'classic' => 'كلاسيكي',
            'minimalist' => 'بسيط',
            'luxury' => 'فاخر',
            'traditional' => 'تقليدي',
            'contemporary' => 'معاصر',
            'rustic' => 'ريفي',
            'industrial' => 'صناعي',
        ];
    }
}
