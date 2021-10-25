<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RequestType;

class RequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RequestType::truncate();
        RequestType::create(array(
            'type' => 'Connection',
            'type_lv' => 'Sadarbība'
        ));
        RequestType::create(array(
            'type' => 'Feedback',
            'type_lv' => 'Atgriezeniskā saite'
        ));
    }
}
