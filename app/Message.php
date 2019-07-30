<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['body', 'user_id'];

    public function chat()
    {
        return $this->belongsTo('App\Chat');
    }

    public function sender() {

        return $this->belongsTo('App\User', 'sender_id');

    }
}
