<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'therapist_id',
        'date',
        'text',
        'mark',
        'is_active'
    ];

    public function patient() {
        return $this->belongsTo('App\Models\Patient');
    }

    public function therapist() {
        return $this->belongsTo('App\Models\Therapist');
    }
}
