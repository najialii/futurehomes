<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'version_number',
        'title',
        'content',
        'meta_description',
        'changes',
        'created_by',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    
    public function getDifferences(): array
    {
        $previousVersion = static::where('page_id', $this->page_id)
            ->where('version_number', '<', $this->version_number)
            ->orderBy('version_number', 'desc')
            ->first();

        if (!$previousVersion) {
            return ['type' => 'initial', 'changes' => []];
        }

        $changes = [];

        if ($this->title !== $previousVersion->title) {
            $changes['title'] = [
                'from' => $previousVersion->title,
                'to' => $this->title,
            ];
        }

        if ($this->content !== $previousVersion->content) {
            $changes['content'] = [
                'from' => $previousVersion->content,
                'to' => $this->content,
            ];
        }

        if ($this->meta_description !== $previousVersion->meta_description) {
            $changes['meta_description'] = [
                'from' => $previousVersion->meta_description,
                'to' => $this->meta_description,
            ];
        }

        return ['type' => 'update', 'changes' => $changes];
    }
}
