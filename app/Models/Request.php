<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'therapist_id',
        'type_id',
        'status_id'
    ];

    public function patient() {
        return $this->belongsTo('App\Models\Patient');
    }

    public function therapist() {
        return $this->belongsTo('App\Models\Therapist');
    }

    public function type() {
        return $this->belongsTo('App\Models\RequestType');
    }

    public function status() {
        return $this->belongsTo('App\Models\Status');
    }
}
