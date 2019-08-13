<?php

namespace App\Http\Middleware;

use Closure;
use Morilog\Jalali\CalendarUtils;

class JalaliDate
{
    public static function p2e($inp) {
        $out = str_replace('۰', '0', $inp);
        $out = str_replace('۱', '1', $out);
        $out = str_replace('۲', '2', $out);
        $out = str_replace('۳', '3', $out);
        $out = str_replace('۴', '4', $out);
        $out = str_replace('۵', '5', $out);
        $out = str_replace('۶', '6', $out);
        $out = str_replace('۷', '7', $out);
        $out = str_replace('۸', '8', $out);
        $out = str_replace('۹', '9', $out);
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
        return $dateTmp[0] . '/' . JalaliDate::twoDigit($dateTmp[1]) . '/' . JalaliDate::twoDigit($dateTmp[2]);
    }

    public static function j2g($inp) {
        $dateTmp = explode(' ', JalaliDate::p2e($inp));
        $dateTmp = explode('/', $dateTmp[0]);
        if((int)$dateTmp[0]>(int)$dateTmp[2]) {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        }else {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[2], (int)$dateTmp[1], (int)$dateTmp[0]);
        }
        return $dateTmp[0] . '-' . JalaliDate::twoDigit($dateTmp[1]) . '-' . JalaliDate::twoDigit($dateTmp[2]);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $reqs = $request->all();
        foreach($reqs as $key=>$value) {
            if(strpos(strtolower($key), 'date')!==false) {
                $reqs[$key] = JalaliDate::j2g($value);
            }
        }
        $request->replace($reqs);
        return $next($request);
    }
}
