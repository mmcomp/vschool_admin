<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SequenceDetail extends Model
{
    public function user() {
        return $this->belongsTo('App\Users', 'user_id');
    }

    public function sequence() {
        return $this->belongsTo('App\Sequence', 'sequences_id');
    }
}
