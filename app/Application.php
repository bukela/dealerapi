<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Application extends Eloquent

{
    protected $guarded = [];

    protected $dates = ['created_at'];
    
    public function financial() {

        return $this->hasOne('App\FinancialInformation');

    }

    public function employment() {

        return $this->hasOne('App\Employment');

    }

    public function general() {

        return $this->hasOne('App\ApplicationGeneral');

    }

    public function loan_detail() {

        return $this->hasOne('App\LoanDetail');

    }

    public function previous_employment() {

        return $this->hasOne('App\PreviousEmployment');

    }

    public function coapplicant() {

        return $this->hasOne('App\CoApplicant');

    }

    public function previous_address() {

        return $this->hasOne('App\PreviousAddress');

    }

    public function home_own() {

        return $this->hasOne('App\HomeOwnershipDetail');

    }

    public function files() {

        return $this->hasMany('App\File');

    }

    public function about_equipment() {

        return $this->hasOne('App\EquipmentType');

    }

    public function current_address() {

        return $this->hasOne('App\CurrentAddress');

    }
    
}
