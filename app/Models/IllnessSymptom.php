<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IllnessSymptom extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'illnesses_and_symptoms';

    /**
     * @var string[]
     */
    protected $fillable = [
        'illness_id',
        'symptom_id'
    ];

    /**
     * @return BelongsTo
     */
    public function illness() {
        return $this->belongsTo('App\Models\MentalIllness');
    }

    /**
     * @return BelongsTo
     */
    public function symptom() {
        return $this->belongsTo('App\Models\Symptom');
    }
}
