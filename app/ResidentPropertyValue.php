<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidentPropertyValue extends Model
{
    public function propertyfield() {
        return $this->belongsTo('App\ResidentPropertyField', 'redient_property_fields_id');
    }
}
