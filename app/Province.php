<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['abbr', 'name'];
}
