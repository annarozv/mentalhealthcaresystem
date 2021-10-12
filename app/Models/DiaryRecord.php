<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaryRecord extends Model
{
    use HasFactory;

    protected $table = '';

    protected $fillable = [
        'patient_id',
        'author_id',
        'state_id',
        'date',
        'record_text',
        'is_active'
    ];

    public function patient() {
        return $this->belongsTo('App\Models\Patient');
    }

    public function author() {
        return $this->belongsTo('App\Models\User');
    }

    public function state() {
        return $this->belongsTo('App\Models\State');
    }

    public function symptoms() {
        return $this->hasMany('App\Models\DiaryRecordSymptom');
    }
}
