<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCatagoryRestriction extends Model
{
    public function catagory() {
        return $this->belongsTo('App\ResidentCatagory', 'resident_catagories_id');
    }
}
