<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidentNickname extends Model
{
    public function field() {
        return $this->belongsTo('App\ResidentPropertyField', 'resident_property_fields_id');
    }
}
