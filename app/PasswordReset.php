<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable = [
        'email', 'token'
    ];
}
