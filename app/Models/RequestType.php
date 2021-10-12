<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_name',
        'type_name_lv'
    ];

    public function requests() {
        return $this->hasMany('App\Models\Request');
    }
}
