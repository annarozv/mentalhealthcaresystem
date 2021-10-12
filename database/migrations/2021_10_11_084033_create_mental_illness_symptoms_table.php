<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMentalIllnessSymptomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mental_illness_symptoms', function (Blueprint $table) {
            $table->id();
            $table->string('symptom_name', 255)->unique();
            $table->string('symptom_name_lv', 255)->unique();
            $table->text('description');
            $table->text('description_lv');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mental_illness_symptoms');
    }
}
