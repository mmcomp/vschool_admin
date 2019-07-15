<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\CalendarUtils;

use App\User;
use App\ResidentCoinTransaction;
use App\ResidentUserUsage;

class StatisticsController extends Controller
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

    public static function j2m($inp) {
        $inp = StatisticsController::p2e($inp);
        $dateTmp = explode('/', $inp);
        if(count($dateTmp)!=3) {
            return false;
        }
        if((int)$dateTmp[0]>(int)$dateTmp[2]) {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        }else {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[2], (int)$dateTmp[1], (int)$dateTmp[0]);
        }
        return $dateTmp[0] . '-' . $dateTmp[1] . '-' . $dateTmp[2] . ' 00:00:00';
    }

    public static function m2j($inp) {
        $dateTmp = explode('-', $inp);
        if(count($dateTmp)!=3) {
            return false;
        }
        if((int)$dateTmp[0]>(int)$dateTmp[2]) {
            $dateTmp = CalendarUtils::toJalali((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        }else {
            $dateTmp = CalendarUtils::toJalali((int)$dateTmp[2], (int)$dateTmp[1], (int)$dateTmp[0]);
        }
        return $dateTmp[0] . '/' . $dateTmp[1] . '/' . $dateTmp[2];
    }

    public function coins(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $icons = [
            "success"=>"check",
            "danger"=>"ban"
        ];
        $msgs = [];
        $sessions = $request->session()->all();
        foreach($sessions as $key=>$value) {
            if(strpos($key, 'msg_')!==false && isset($icons[str_replace('msg_', '', $key)])) {
                $msgs[] = [
                    "msg"=>$value,
                    "type"=>str_replace('msg_', '', $key),
                    "icon"=>$icons[str_replace('msg_', '', $key)],
                ];
            }
        }
        $fromDate = '';
        if(isset($request->all()['from-date']) && trim($request->all()['from-date'])!=''){
            $fromDate = trim($request->all()['from-date']);
        }
        $toDate = '';
        if(isset($request->all()['to-date']) && trim($request->all()['to-date'])!=''){
            $toDate = trim($request->all()['to-date']);
        }

        $coinStatsInc = ResidentCoinTransaction::where('process_type', 'user')
        ->with('user')
        ->where('type', 'increase')
        ->select(DB::raw('sum(amount) as user_total, process_id'))
        ->where(function($query) use ($request) {
            if(isset($request->all()['from-date']) && trim($request->all()['from-date'])!=''){
                $fromDate = StatisticsController::j2m($request->all()['from-date']);
                $query->where('created_at', '>=', $fromDate);
            }
            if(isset($request->all()['to-date']) && trim($request->all()['to-date'])!=''){
                $toDate = StatisticsController::j2m($request->all()['to-date']);
                $toDate = str_replace('00:00:00', '23:59:59', $toDate);
                $query->where('created_at', '<=', $toDate);
            }
        })
        ->groupBy('process_id')->get();

        $coinStatsDec = ResidentCoinTransaction::where('process_type', 'user')
        ->with('user')
        ->where('type', 'decrease')
        ->select(DB::raw('sum(amount) as user_total, process_id'))
        ->where(function($query) use ($request) {
            if(isset($request->all()['from-date']) && trim($request->all()['from-date'])!=''){
                $fromDate = StatisticsController::j2m($request->all()['from-date']);
                $query->where('created_at', '>=', $fromDate);
            }
            if(isset($request->all()['to-date']) && trim($request->all()['to-date'])!=''){
                $toDate = StatisticsController::j2m($request->all()['to-date']);
                $query->where('created_at', '<=', $toDate);
            }
        })
        ->groupBy('process_id')->get();

        $users = [];
        foreach($coinStatsInc as $coinInc) {
            if(!isset($users[$coinInc->process_id])) {
                $users[$coinInc->process_id] = [
                    "name"=>$coinInc->user->name,
                ];
            }
            $users[$coinInc->process_id]['inc'] = $coinInc->user_total;
        }
        foreach($coinStatsDec as $coinDec) {
            if(!isset($users[$coinDec->process_id])) {
                $users[$coinDec->process_id] = [
                    "name"=>$coinDec->user->name,
                ];
            }
            $users[$coinDec->process_id]['dec'] = $coinDec->user_total;
        }
        $empUsers = $users;
        $users = [];
        foreach($empUsers as $user) {
            $users[] = $user;
        }

        return view('statistics.coins', [
            "fromDate"=>$fromDate,
            "toDate"=>$toDate,
            "users"=>$users,
            "msgs"=>$msgs,
        ]);
    }


    public function usages(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $icons = [
            "success"=>"check",
            "danger"=>"ban"
        ];
        $msgs = [];
        $sessions = $request->session()->all();
        foreach($sessions as $key=>$value) {
            if(strpos($key, 'msg_')!==false && isset($icons[str_replace('msg_', '', $key)])) {
                $msgs[] = [
                    "msg"=>$value,
                    "type"=>str_replace('msg_', '', $key),
                    "icon"=>$icons[str_replace('msg_', '', $key)],
                ];
            }
        }
        $fromDate = '';
        if(isset($request->all()['from-date']) && trim($request->all()['from-date'])!=''){
            $fromDate = trim($request->all()['from-date']);
        }
        $toDate = '';
        if(isset($request->all()['to-date']) && trim($request->all()['to-date'])!=''){
            $toDate = trim($request->all()['to-date']);
        }

        $usageData = ResidentUserUsage::with('user')
        ->select(DB::raw('sum(usage_count) as user_total, user_id'))
        ->where(function($query) use ($request) {
            if(isset($request->all()['from-date']) && trim($request->all()['from-date'])!=''){
                $fromDate = StatisticsController::j2m($request->all()['from-date']);
                $query->where('created_at', '>=', $fromDate);
                // echo "created_at >= '" . $fromDate . "'<br/>";
            }
            if(isset($request->all()['to-date']) && trim($request->all()['to-date'])!=''){
                $toDate = StatisticsController::j2m($request->all()['to-date']);
                $toDate = str_replace('00:00:00', '23:59:59', $toDate);
                $query->where('updated_at', '<=', $toDate);
                // echo "updated_at <= '" . $toDate . "'<br/>";
                // die();
            }
        })
        ->groupBy('user_id')->get();

        return view('statistics.usages', [
            "fromDate"=>$fromDate,
            "toDate"=>$toDate,
            "usageData"=>$usageData,
            "msgs"=>$msgs,
        ]);
    }
}
