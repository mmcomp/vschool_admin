<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\CalendarUtils;

class School extends Model
{
    public $timestamps = false;
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

    public function getCreatedDateAttribute($value) {
        if($value) {
            return self::g2j($value);
        }else {
            return $value;
        }
    }
    public function zone() {
        return $this->belongsTo('App\Zone', 'zones_id');
    }
}
