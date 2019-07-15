<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Protocol extends Model
{
    public function employer_agent() {
        return $this->belongsTo('App\Agent', 'employer_id');
    }

    public function contractor_agent() {
        return $this->belongsTo('App\Agent', 'contractor_id');
    }

    public function employer() {
        return $this->belongsTo('App\Company', 'employer_company_id');
    }

    public function contractor() {
        return $this->belongsTo('App\Company', 'contractor_company_id');
    }

    public function docs() {
        return $this->hasMany('App\ProtocolDoc', 'protocols_id');
    }

    public function type() {
        return $this->belongsTo('App\ProtocolType', 'protocol_types_id');
    }
}
