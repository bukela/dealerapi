<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Tymon\JWTAuth\Contracts\JWTSubject;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
// use DesignMyNight\Mongodb\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{

    // use HasApiTokens, Notifiable;
    use Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'user_id' => $this->id,
            'email' => $this->email,
            'exp' => \Carbon\Carbon::now()->addDays(15)->timestamp
        ];
    }

    protected $collection = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'last_login'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function applications() {

        return $this->hasMany('App\Application');

    }

    public function user_role() {

        return $this->belongsTo('App\Role');
    }

    public function messages_sent() {

        return $this->hasMany('App\Message', 'sender_id');

    }

    public function messages_received() {

        return $this->hasMany('App\Message', 'receiver_id');

    }

    public function all_user_messages() {

        return $this->messages_sent->merge($this->messages_received);

    }

    public function users_created() {

        return $this->hasMany('App\User', 'parent_id');

    }

    public function sent_contacts() {

        return $this->hasMany('App\ContactUs');

    }

    public function received_contacts() {

        return $this->hasMany('App\ContactUs', 'receiver_id');

    }


    public function chats() {

        return $this->hasMany('App\Chat', 'users');
    }

}
