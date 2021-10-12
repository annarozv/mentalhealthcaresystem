<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Symptom extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'mental_illness_symptoms';

    /**
     * @var string[]
     */
    protected $fillable = [
        'symptom_name',
        'symptom_name_lv',
        'description',
        'description_lv',
        'is_active'
    ];

    /**
     * @return HasMany
     */
    public function illnesses() {
        return $this->hasMany('App\Models\IllnessSymptom');
    }

    /**
     * @return HasMany
     */
    public function records() {
        return $this->hasMany('App\Models\DiaryRecordSymptom');
    }
}
