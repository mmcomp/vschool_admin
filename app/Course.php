<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public $timestamps = false;

    public function pages() {
        return $this->hasMany('App\Question', 'courses_id', 'id');
    }
}
