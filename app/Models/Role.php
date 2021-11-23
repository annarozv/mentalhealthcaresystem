<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    /**
     * @const for Administrator role name
     */
    public const ADMIN_ROLE = 'Administrator';

    /**
     * @const for Moderator role name
     */
    public const MODERATOR = 'Moderator';

    /**
     * @const for Therapist role name
     */
    public const THERAPIST = 'Therapist';

    /**
     * @const for Patient role name
     */
    public const PATIENT = 'Patient';

    /**
     * @var string[]
     */
    protected $fillable = [
        'role',
        'role_lv'
    ];

    /**
     * @return HasMany
     */
    public function users() {
        return $this->hasMany('App\Models\User');
    }
}
