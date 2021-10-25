<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            GenderSeeder::class,
            IllnessAndSymptomSeeder::class,
            StatusSeeder::class,
            StateSeeder::class,
            RequestTypeSeeder::class
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
