<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $casts = [
        'page'=>'object'
    ];

    public function lesson() {
        return $this->belongsTo('App\Lesson', 'lessons_id');
    }

    public function question() {
        return $this->hasOne('App\Question', 'pages_id');
    }
}
