<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaryRecordSymptom extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'diary_records_and_symptoms';

    /**
     * @var string[]
     */
    protected $fillable = [
        'record_id',
        'symptom_id'
    ];

    public function record() {
        return $this->belongsTo('App\Models\DiaryRecord');
    }

    public function symptom() {
        return $this->belongsTo('App\Models\Symptom');
    }
}
