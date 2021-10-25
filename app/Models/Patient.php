<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'date_of_birth',
        'gender_id',
        'profile_picture',
        'additional_information',
        'user_id',
        'is_active'
    ];

    /**
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return BelongsTo
     */
    public function gender() {
        return $this->belongsTo('App\Models\Gender');
    }

    /**
     * @return HasMany
     */
    public function reviews() {
        return $this->hasMany('App\Models\Review');
    }

    /**
     * @return HasMany
     */
    public function requests() {
        return $this->hasMany('App\Models\Request');
    }

    /**
     * @return HasMany
     */
    public function records() {
        return $this->hasMany('App\Models\DiaryRecord');
    }
}
