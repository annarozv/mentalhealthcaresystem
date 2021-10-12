<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IllnessSymptom extends Model
{
    use HasFactory;

    protected $table = 'illnesses_and_symptoms';

    protected $fillable = [
        'illness_id',
        'symptom_id'
    ];

    public function illness() {
        return $this->belongsTo('App\Models\MentalIllness');
    }

    public function symptom() {
        return $this->belongsTo('App\Models\Symptom');
    }
}
