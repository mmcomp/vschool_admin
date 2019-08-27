<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    public $timestamps = false;

    public function chapter() {
        return $this->belongsTo('App\Chapter', 'chapters_id');
    }

    public function pages() {
        return $this->hasMany('App\Page', 'lessons_id', 'id');
    }
}
