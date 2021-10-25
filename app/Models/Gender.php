<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gender extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'gender',
        'gender_lv'
    ];

    /**
     * @return HasMany
     */
    public function patients() {
        return $this->hasMany('App\Models\Patient');
    }

    /**
     * @return HasMany
     */
    public function therapists() {
        return $this->hasMany('App\Models\Therapists');
    }
}
