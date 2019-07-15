<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TournamentJoin extends Model
{
    public $table = "tournamet_joins";

    public function resident() {
        return $this->belongsTo('App\Resident', 'resident_id');
    }
}
