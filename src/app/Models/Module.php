<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];

    public function modulePermissions()
    {
        return $this->hasMany(ModulePermission::class, 'module_id');
    }
}
