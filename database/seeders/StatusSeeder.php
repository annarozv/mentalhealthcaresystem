<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::truncate();
        Status::create(array(
            'status' => 'Initiated by patient',
            'status_lv' => 'Pacients izveidoja'
        ));
        Status::create(array(
            'status' => 'Approved',
            'status_lv' => 'Apstiprināts'
        ));
        Status::create(array(
            'status' => 'Refused by therapist',
            'status_lv' => 'Terapeits noraidīja'
        ));
        Status::create(array(
            'status' => 'Removed by patient',
            'status_lv' => 'Pacients noņema'
        ));
    }
}
