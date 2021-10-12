<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::create(array(
            'name' => 'Admin',
            'surname' => 'Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('admin123'),
            'role_id' => 1
        ));
        User::create(array(
            'name' => 'Moderator',
            'surname' => 'Test',
            'email' => 'moder@test.com',
            'password' => bcrypt('moder123'),
            'role_id' => 2
        ));
        User::create(array(
            'name' => 'Doctor',
            'surname' => 'Test',
            'email' => 'doc@test.com',
            'password' => bcrypt('doc123'),
            'role_id' => 3
        ));
        User::create(array(
            'name' => 'Patient',
            'surname' => 'Test',
            'email' => 'patient@test.com',
            'password' => bcrypt('pat123'),
            'role_id' => 4
        ));
    }
}
