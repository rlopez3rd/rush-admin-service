<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class UserShowResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $roles = $this->roles->map(function ($role) {
            $rolePermissionIds = $role->modulePermissions->pluck('id')->toArray();

            // dd($role->modulePermissions->groupBy('module_id'));

            $modules = $role->modulePermissions
                ->groupBy('module_id')
                ->map(function ($permissions) use ($rolePermissionIds) {
                    $module = $permissions->first()->module;
                    $filteredPermissions = $permissions
                        ->filter(function ($permission) use($rolePermissionIds) {
                            return in_array($permission->id, $rolePermissionIds);
                        })
                        ->pluck('name')
                        ->toArray();

                    return [
                        'id'=> $module->id,
                        'key' => Str::snake($module->name),
                        'name' => $module->name,
                        'permissions' => $filteredPermissions
                    ];
                })
                ->values();

            return [
                'id' => $role->id,
                'name' => $role->name,
                'modules' => $modules
            ];
        });

        
        return [
            'id' => $this->id ?? null,
            'firstname' => $this->firstname ?? null,
            'lastname' => $this->lastname ?? null,
            'username' => $this->username ?? null,
            'email' => $this->email ?? null,
            'phone_number' => $this->phone_number ?? null,
            'address' => $this->address ?? null,
            'postcode' => $this->postcode ?? null,
            'updated_at' => @$this->updated_at?->format('m/d/Y H:i:s a') ?? null,
            'roles' => $roles
        ];
    }
}
