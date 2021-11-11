<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    /**
     * @const for status 'Approved'
     */
    public const APPROVED = 'Approved';

    /**
     * @const for status 'Initiated by patient'
     */
    public const INITIATED = 'Initiated by patient';

    /**
     * @const for status 'Refused by therapist'
     */
    public const REFUSED = 'Refused by therapist';

    /**
     * @const for status 'Removed by patient'
     */
    public const REMOVED = 'Removed by patient';

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
