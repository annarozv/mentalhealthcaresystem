<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'patient_id',
        'therapist_id',
        'type_id',
        'status_id'
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

    /**
     * @return BelongsTo
     */
    public function type() {
        return $this->belongsTo('App\Models\RequestType');
    }

    /**
     * @return BelongsTo
     */
    public function status() {
        return $this->belongsTo('App\Models\Status');
    }
}
