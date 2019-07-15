<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidentCoinTransaction extends Model
{
    public function user() {
        return $this->belongsTo('App\Users', 'process_id');
    }
}
