<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidentSignRelation extends Model
{
    public function sign() {
        return $this->belongsTo('App\ResidentSign', 'resident_signs_id');
    }
}
