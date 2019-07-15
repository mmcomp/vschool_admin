<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    public function sequence() {
        return $this->belongsTo('App\Sequence', 'sequences_id');
    }
}
