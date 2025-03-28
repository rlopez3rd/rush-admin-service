<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{
    
    protected $fillable = [
        'module_id',
        'name',
        'created_at',
        'updated_at',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_module_permissions', 'module_permission_id', 'role_id');
    }
}
