<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    public function city() {
        return $this->belongsTo('App\City', 'cities_id');
    }
}
