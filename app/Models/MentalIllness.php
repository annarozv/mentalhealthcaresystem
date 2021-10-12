<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MentalIllness extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'illness_name',
        'illness_name_lv',
        'description',
        'description_lv',
        'is_active'
    ];

    /**
     * @return HasMany
     */
    public function symptoms() {
        return $this->hasMany('App\Models\IllnessSymptom');
    }
}
