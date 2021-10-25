<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gender::truncate();
        Gender::create(array(
            'gender' => 'Female',
            'gender_lv' => 'Sieviete'
        ));
        Gender::create(array(
            'gender' => 'Male',
            'gender_lv' => 'Vīrietis'
        ));
        Gender::create(array(
            'gender' => 'Non-Binary',
            'gender_lv' => 'Nebinārs'
        ));
        Gender::create(array(
            'gender' => 'Other',
            'gender_lv' => 'Cits'
        ));
        Gender::create(array(
            'gender' => 'Prefer not to say',
            'gender_lv' => 'Nevēlos teikt'
        ));
    }
}
