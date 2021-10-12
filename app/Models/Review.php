<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'patient_id',
        'therapist_id',
        'date',
        'text',
        'mark',
        'is_active'
    ];

    /**
     * @return BelongsTo
     */
    public function patient() {
        return $this->belongsTo('App\Models\Patient');
    }

    /**
     * @return BelongsTo
     */
    public function therapist() {
        return $this->belongsTo('App\Models\Therapist');
    }
}
