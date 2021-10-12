<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'state',
        'state_lv'
    ];

    /**
     * @return HasMany
     */
    public function records() {
        return $this->hasMany('App\Models\DiaryRecord');
    }
}
