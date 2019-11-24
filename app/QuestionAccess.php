<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionAccess extends Model
{
    public $timestamps = false;
    protected $table = 'question_accesses';

    public function course() {
        return $this->belongsTo('App\Course', 'courses_id');
    }
}
