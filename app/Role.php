<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Role extends Model
{
    public function users(){

        return $this->hasMany('App\User');

    }
}
