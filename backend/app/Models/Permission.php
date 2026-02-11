<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'resource',
        'action',
    ];

    
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    
    public static function createName(string $resource, string $action): string
    {
        return "{$resource}.{$action}";
    }

    
    public static function byResource(string $resource)
    {
        return static::where('resource', $resource)->get();
    }

    
    public static function byAction(string $action)
    {
        return static::where('action', $action)->get();
    }
}