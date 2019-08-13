<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Protocol extends Model
{
    public static function e2p($inp) {
        $out = str_replace('0', '۰', $inp);
        $out = str_replace('1', '۱', $out);
        $out = str_replace('2', '۲', $out);
        $out = str_replace('3', '۳', $out);
        $out = str_replace('4', '۴', $out);
        $out = str_replace('5', '۵', $out);
        $out = str_replace('6', '۶', $out);
        $out = str_replace('7', '۷', $out);
        $out = str_replace('8', '۸', $out);
        $out = str_replace('9', '۹', $out);
        return $out;
    }

    public static function twoDigit($inp) {
        $out = (int)$inp;
        if($out<10) {
            $out = '0' . $out;
        }else {
            $out = "$out";
        }
        return $out;
    }

    public static function g2j($inp) {
        $dateTmp = explode(' ', $inp);
        $dateTmp = explode('-', $dateTmp[0]);
        $dateTmp = CalendarUtils::toJalali((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        return self::e2p($dateTmp[0] . '/' . self::twoDigit($dateTmp[1]) . '/' . self::twoDigit($dateTmp[2]));
    }

    public function getStartAttribute($value) {
        if($value) {
            return self::g2j($value);
        }else {
            return $value;
        }
    }

    public function getEndAttribute($value) {
        if($value) {
            return self::g2j($value);
        }else {
            return $value;
        }
    }

    public function getRegisterAttribute($value) {
        if($value) {
            return self::g2j($value);
        }else {
            return $value;
        }
    }

    public function getCreatedAtAttribute($value) {
        if($value) {
            return self::g2j($value);
        }else {
            return $value;
        }
    }

    public function getUpdatedAtAttribute($value) {
        if($value) {
            return self::g2j($value);
        }else {
            return $value;
        }
    }

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
