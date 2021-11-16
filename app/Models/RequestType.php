<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestType extends Model
{
    use HasFactory;

    /**
     * @const for connection request type
     */
    public const CONNECTION = 'Connection';

    /**
     * @const for feedback request type
     */
    public const FEEDBACK = 'Feedback';

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
