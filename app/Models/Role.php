<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'role_name',
        'role_name_lv'
    ];

    /**
     * @return HasMany
     */
    public function users() {
        return $this->hasMany('App\Models\User');
    }
}
