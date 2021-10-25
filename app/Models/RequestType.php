<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestType extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'type_lv'
    ];

    /**
     * @return HasMany
     */
    public function requests() {
        return $this->hasMany('App\Models\Request');
    }
}
