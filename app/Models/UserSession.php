<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model

{

    use HasFactory;


    protected $fillable = ['user_id', 'login_at', 'logout_at'];


    protected $casts = [

        'login_at' => 'datetime',

        'logout_at' => 'datetime',

    ];


    public function user()

    {

        return $this->belongsTo(User::class);
    }
    
}
