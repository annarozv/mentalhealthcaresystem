<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'role_id',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Foreign key relations
     */

    /**
     * @return BelongsTo
     */
    public function role() {
        return $this->belongsTo('App\Models\Role');
    }

    /**
     * @return HasOne
     */
    public function patient() {
        return $this->hasOne('App\Models\Patient');
    }

    /**
     * @return HasOne
     */
    public function therapist() {
        return $this->hasOne('App\Models\Therapist');
    }

    /**
     * @return HasMany
     */
    public function comments() {
        return $this->hasMany('App\Models\Comment');
    }

    /**
     * @return HasMany
     */
    public function records() {
        return $this->hasMany('App\Models\DiaryRecord');
    }

    /**
     * Methods that help to determine user roles
     */

    /**
     * @return bool
     */
    public function isAdmin() {
        return $this->role->role === 'Administrator';
    }

    /**
     * @return bool
     */
    public function isModerator() {
        return $this->role->role === 'Moderator';
    }

    /**
     * @return bool
     */
    public function isTherapist() {
        return $this->role->role === 'Therapist';
    }

    /**
     * @return bool
     */
    public function isPatient() {
        return $this->role->role === 'Patient';
    }
}
