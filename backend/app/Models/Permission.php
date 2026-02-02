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

    /**
     * The roles that belong to the permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Create a permission name from resource and action.
     */
    public static function createName(string $resource, string $action): string
    {
        return "{$resource}.{$action}";
    }

    /**
     * Get permissions by resource.
     */
    public static function byResource(string $resource)
    {
        return static::where('resource', $resource)->get();
    }

    /**
     * Get permissions by action.
     */
    public static function byAction(string $action)
    {
        return static::where('action', $action)->get();
    }
}