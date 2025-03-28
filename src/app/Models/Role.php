<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'is_admin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function modulePermissions()
    {
        return $this->belongsToMany(ModulePermission::class, 'role_module_permissions', 'role_id', 'module_permission_id');
    }

    // public function module()
    // {
    //     return $this->hasManyThrough(Module::class, RoleModulePermission::class, 'role_id', '')
    // }
}
