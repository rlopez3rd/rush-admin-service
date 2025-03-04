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
        $this->belongsToMany(User::class);
    }
}
