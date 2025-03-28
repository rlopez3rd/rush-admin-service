<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\ModulePermission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleModulePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::beginTransaction();
        try {
            $module = Module::updateOrCreate(['name' => 'Event Builder']);

            $modulePermissions = $module->modulePermissions()->saveMany([
                    new ModulePermission(['name' => 'view']),
                    new ModulePermission(['name' => 'add']),
                    new ModulePermission(['name' => 'edit']),
                    new ModulePermission(['name' => 'delete'])
                ]
            );

            // $modulePermissions = $module->modulePermissions()->whereIn('id', [1,2,3,4])->get();
            // dd(collect($modulePermissions)->pluck('id'));

            $modulePermissionIds = collect($modulePermissions)->pluck('id');

            $role = Role::where('name', 'Admin')->first();
            $role->modulePermissions()->attach([$modulePermissionIds]);

            // DB::commit();
        } catch (\Exception $e) {
            // DB::rollBack();
        }
       

    }
}
