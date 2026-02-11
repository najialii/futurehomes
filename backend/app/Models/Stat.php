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

    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
