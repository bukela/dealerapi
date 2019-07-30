<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Permission extends Model
{
    
    public function users() {

        return $this->belongsToMany('App\User', 'user_permission');

    }
}
