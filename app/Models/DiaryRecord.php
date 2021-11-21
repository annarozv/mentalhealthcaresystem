<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiaryRecord extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'diary_records';

    /**
     * @var string[]
     */
    protected $fillable = [
        'patient_id',
        'author_id',
        'state_id',
        'date',
        'record_text',
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
    public function author() {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return BelongsTo
     */
    public function state() {
        return $this->belongsTo('App\Models\State');
    }

    /**
     * @return HasMany
     */
    public function symptoms() {
        return $this->hasMany('App\Models\DiaryRecordSymptom', 'record_id');
    }

    /**
     * @return HasMany
     */
    public function comments() {
        return $this->hasMany('App\Models\Comment', 'record_id');
    }
}
