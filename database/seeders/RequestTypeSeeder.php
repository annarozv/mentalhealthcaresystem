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
            'type_name' => 'Connection',
            'type_name_lv' => 'Sadarbība'
        ));
        RequestType::create(array(
            'type_name' => 'Feedback',
            'type_name_lv' => 'Atgriezeniskā saite'
        ));
    }
}
