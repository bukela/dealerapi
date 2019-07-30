<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['receiver_id', 'sender_id','users'];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }
}
