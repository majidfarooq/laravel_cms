<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, softDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'provider_id',
        'type',
        'image',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'zipcode',
        'phone',
        'facebook_url',
        'instagram_url',
        'interests',
        'role_id',
        'stripeId',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
