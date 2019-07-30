<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'application_id', 'filename', 'file_type_code'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $appends = ['name', 'application_name'];

    function getNameAttribute() {

        return substr($this->filename, strpos($this->filename, '-') + 1);

    }

    function getApplicationNameAttribute() {

        return $this->application->name;

    }


    public function file_type() {

        return $this->belongsTo('App\FileType', 'file_type_code', 'code');

    }

    public function application() {

        return $this->belongsTo('App\Application');

    }
}
