<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProperty extends Model
{
    public function propertyfield() {
        return $this->belongsTo('App\ResidentPropertyField', 'redisdent_propery_fields_id');
    }

    public function zone() {
        return $this->belongsTo('App\Zone', 'zones_id');
    }
}
