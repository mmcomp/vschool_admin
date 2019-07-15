<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidentSignProgress extends Model
{
    protected $table = 'resident_sign_progress';

    public function sign() {
        return $this->belongsTo('App\ResidentSign', 'resident_signs_id');
    }
}
