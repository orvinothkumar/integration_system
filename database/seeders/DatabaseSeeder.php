<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'administrator']);
        Role::create(['name' => 'admin/customer']);
        Role::create(['name' => 'user/guest']);
        // \App\Models\User::factory(10)->create();
        DB::table('users')->insert([
            'name' => 'Admin',
            'first_name' => 'Admin',
            'last_name' => '',
            'username' => 'admin',
            'email' => 'admin@emsolautomation.com',
            'password' => bcrypt('emsol@2021'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'role_id' => 1,
            'mobile' => '9876543210',
            'dob' => '01-01-1971',
            'address' => 'New No: 20, Old No: 8, First Floor, 3rd Main Road, Kannan Nagar, Madipakkam, Chennai-600091',
            'validity_date' => date('Y-m-d H:i:s', strtotime('01-12-2050')),
            'created_by' => 0,
            'is_verified' => 1,
            'api_token' => '',
            'status' => 1
        ]);
        DB::table('users')->insert([
            'name' => 'Emsol User',
            'first_name' => 'Emsol',
            'last_name' => 'User',
            'username' => 'emsol',
            'email' => 'user@emsolautomation.com',
            'password' => bcrypt('emsol@2021'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'role_id' => 2,
            'mobile' => '9876543210',
            'dob' => '01-01-1971',
            'address' => 'New No: 20, Old No: 8, First Floor, 3rd Main Road, Kannan Nagar, Madipakkam, Chennai-600091',
            'validity_date' => date('Y-m-d H:i:s', strtotime('01-12-2050')),
            'created_by' => 1,
            'is_verified' => 1,
            'api_token' => '',
            'status' => 1
        ]);
    }
}
