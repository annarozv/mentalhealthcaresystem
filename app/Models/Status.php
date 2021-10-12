<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'status',
        'status_lv'
    ];

    /**
     * @return HasMany
     */
    public function requests() {
        return $this->hasMany('App\Models\Request');
    }
}
