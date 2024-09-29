<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'telephone',
        'username',
        'alamat',
        'profile_pictures',
        'bio'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($role)
    {
        return $this->role && $this->role->name === $role;
    }
    // User.php (Model)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function chats()
    {
        return $this->hasMany(Chat::class, 'user_id'); // Menghubungkan ke kolom user_id di tabel chats
    }

    public function sessions()

    {

        return $this->hasMany(UserSession::class);
    }

    public function getTotalLoginTimeAttribute()

    {

        return $this->sessions->reduce(function ($carry, $session) {

            if ($session->logout_at) {

                return $carry + $session->logout_at->diffInSeconds($session->login_at);
            }

            return $carry;
        }, 0);
    }

    public function getAverageLoginTimeAttribute()

    {

        $sessionCount = $this->sessions->count();

        return $sessionCount > 0 ? $this->total_login_time / $sessionCount : 0;
    }



    
}

