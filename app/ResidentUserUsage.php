<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidentUserUsage extends Model
{
    public function user() {
        return $this->belongsTo('App\Users', 'user_id');
    }
}
