<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function ceo() {
        return $this->belongsTo('App\Agent', 'ceo_agents_id');
    }

    public function city() {
        return $this->belongsTo('App\City', 'cities_id');
    }

    public function service() {
        return $this->belongsTo('App\Service', 'services_id');
    }

    public function ownership() {
        return $this->belongsTo('App\Ownership', 'ownerships_id');
    }
}
