<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResidentCatagoryRelation extends Model
{
    protected $table = 'resident_catagory_relation';

    public function catagory() {
        return $this->belongsTo('App\ResidentCatagory', 'resident_catagory_id');
    }
}
