<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();
        Role::create(array(
            'role_name' => 'Administrator',
            'role_name_lv' => 'Administrators'
        ));
        Role::create(array(
            'role_name' => 'Moderator',
            'role_name_lv' => 'Moderators'
        ));
        Role::create(array(
            'role_name' => 'Therapist',
            'role_name_lv' => 'Terapeits'
        ));
        Role::create(array(
            'role_name' => 'Patient',
            'role_name_lv' => 'Pacients'
        ));
    }
}
