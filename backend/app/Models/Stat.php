<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use Auditable;

    protected $fillable = [
        'name',
        'number',
        'icon',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active stats
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get stats ordered by display_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
