<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    public function level() {
        return $this->belongsTo('App\Level', 'resident_level_id');
    }

    public function catagory() {
        return $this->hasMany('App\ResidentCatagoryRelation', 'resident_id');
    }

    public function property() {
        return $this->hasMany('App\ResidentPropertyValue', 'resident_id');
    }
}
