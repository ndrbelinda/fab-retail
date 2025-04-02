<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
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

    // ==================== JWT Methods ====================
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            // Tambahkan claim custom jika diperlukan
            'role' => $this->role,
            'username' => $this->username
        ];
    }

    // ==================== Role Methods ====================
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

    // ==================== Helper Methods ====================
    /**
     * Get human-readable role name
     */
    public function getRoleName()
    {
        $roles = [
            'staff' => 'Staff',
            'avp' => 'Assistant VP',
            'gm_rcs' => 'GM RCS'
        ];
        
        return $roles[$this->role] ?? 'Unknown';
    }
}