<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ApplicationGeneral extends Eloquent
{
    protected $hidden = [
        'created_at', 'updated_at','application_id'
    ];

    protected $guarded = [];

    public function application() {

        return $this->belongsTo('App\Application');

    }
}
