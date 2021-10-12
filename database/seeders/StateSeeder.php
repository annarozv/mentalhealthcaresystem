<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::truncate();
        State::create(array(
            'state' => 'Great',
            'state_lv' => 'Lieliski'
        ));
        State::create(array(
            'state' => 'Good',
            'state_lv' => 'Labi'
        ));
        State::create(array(
            'state' => 'Normal',
            'state_lv' => 'Normāli'
        ));
        State::create(array(
            'state' => 'Bad',
            'state_lv' => 'Slikti'
        ));
        State::create(array(
            'state' => 'Very bad',
            'state_lv' => 'Ļoti slikti'
        ));
    }
}
