<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $roles = Role::pluck('id', 'name');

        User::create([
            'firstname' => 'admin',
            'lastname' => 'admin',
            'username' => 'admin@email.com',
            'email' => 'admin@email.com',
            'address' => 'Taguig City',
            'phone_number' => '9777777777',
            'password' => 'admin123',
            'postcode' => '123456',
        ])->roles()->attach([$roles['Admin'], $roles['Employee']]);

        User::factory(99)->create()->each(function ($user) use ($roles) {
            $user->roles()->attach($roles['Employee']);
        });
    }
}
