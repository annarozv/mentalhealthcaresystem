<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_of_birth',
        'gender',
        'profile_picture',
        'additional_information',
        'user_id',
        'is_active'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function reviews() {
        return $this->hasMany('App\Models\Review');
    }

    public function requests() {
        return $this->hasMany('App\Models\Request');
    }

    public function records() {
        return $this->hasMany('App\Models\DiaryRecord');
    }
}
