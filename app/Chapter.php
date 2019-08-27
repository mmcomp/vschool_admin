<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    public $timestamps = false;

    public function course() {
        return $this->belongsTo('App\Course', 'courses_id');
    }
}
