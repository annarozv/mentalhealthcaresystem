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
            'role' => 'Administrator',
            'role_lv' => 'Administrators'
        ));
        Role::create(array(
            'role' => 'Moderator',
            'role_lv' => 'Moderators'
        ));
        Role::create(array(
            'role' => 'Therapist',
            'role_lv' => 'Terapeits'
        ));
        Role::create(array(
            'role' => 'Patient',
            'role_lv' => 'Pacients'
        ));
    }
}
