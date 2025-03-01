<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cek apakah user memiliki role staff.
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }

    /**
     * Cek apakah user memiliki role AVP.
     */
    public function isAVP()
    {
        return $this->role === 'avp';
    }

    /**
     * Cek apakah user memiliki role GM RCS.
     */
    public function isGMRCS()
    {
        return $this->role === 'gm_rcs';
    }
}