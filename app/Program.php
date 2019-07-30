<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['credit_program_id', 'name'];
}
