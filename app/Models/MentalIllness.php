<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentalIllness extends Model
{
    use HasFactory;

    protected $fillable = [
        'illness_name',
        'illness_name_lv',
        'description',
        'description_lv',
        'is_active'
    ];

    public function symptoms() {
        return $this->hasMany('App\Models\IllnessSymptom');
    }
}
