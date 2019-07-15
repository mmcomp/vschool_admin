<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidentUserExp extends Model
{
    public function resident() {
        return $this->belongsTo('App\Resident', 'resident_id');
    }
}
