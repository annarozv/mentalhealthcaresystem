<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'state',
        'state_lv'
    ];

    public function records() {
        return $this->hasMany('App\Models\DiaryRecord');
    }
}
