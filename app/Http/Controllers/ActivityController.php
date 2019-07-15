<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\CalendarUtils;

use App\ResidentActivityGroup;
use App\ResidentActivityGroupJoin;
use App\Resident;

class ActivityController extends Controller
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
        return $dateTmp[0] . '/' . ActivityController::twoDigit($dateTmp[1]) . '/' . ActivityController::twoDigit($dateTmp[2]);
    }
    public static function j2g($inp) {
        $dateTmp = explode(' ', ActivityController::p2e($inp));
        $dateTmp = explode('/', $dateTmp[0]);
        if((int)$dateTmp[0]>(int)$dateTmp[2]) {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        }else {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[2], (int)$dateTmp[1], (int)$dateTmp[0]);
        }
        return $dateTmp[0] . '-' . ActivityController::twoDigit($dateTmp[1]) . '-' . ActivityController::twoDigit($dateTmp[2]);
    }
    public function index(Request $request) {
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
        $activities = ResidentActivityGroup::all();
        foreach($activities as $i=>$activity) {
            $activities[$i]->join_limit_date = ActivityController::g2j($activity->join_limit_date);
        }

        return view('activity_groups.index', [
            "activities"=>$activities,
            "msgs"=>$msgs,
        ]);
    }

    public function create(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $activity = new ResidentActivityGroup;

        if(!$request->isMethod('post')) {
            return view('activity_groups.create', [
                "activity"=>$activity
            ]);
        }

        $activity->name = $request->input('name');
        $activity->description = $request->input('description');
        $activity->exp = (int)$request->input('exp');
        $activity->coin = (int)$request->input('coin');
        $activity->max_count = (int)$request->input('max_count');
        $activity->join_limit_date = ActivityController::j2g($request->input('join_limit_date'));

        $activity->save();
        
        $request->session()->flash('msg_success', 'گروه مورد نظر با موفقیت ثبت شد');
        return redirect('/activity_groups');
    }

    public function edit(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $activity = ResidentActivityGroup::find($id);
        if(!$activity) {
            $request->session()->flash('msg_danger', 'گروه مورد نظر پیدا نشد');
            return redirect('/activity_groups');
        }

        $activity->join_limit_date = ActivityController::g2j($activity->join_limit_date);

        if(!$request->isMethod('post')) {
            return view('activity_groups.create', [
                "activity"=>$activity
            ]);
        }

        $activity->name = $request->input('name');
        $activity->description = $request->input('description');
        $activity->exp = (int)$request->input('exp');
        $activity->coin = (int)$request->input('coin');
        $activity->max_count = (int)$request->input('max_count');
        $activity->join_limit_date = ActivityController::j2g($request->input('join_limit_date'));

        $activity->save();
        
        $request->session()->flash('msg_success', 'گروه مورد نظر با موفقیت بروز شد');
        return redirect('/activity_groups');
    }

    public function delete(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $activity = ResidentActivityGroup::find($id);
        if(!$activity) {
            $request->session()->flash('msg_danger', 'گروه مورد نظر پیدا نشد');
            return redirect('/activity_groups');
        }

        $activity->delete();
        $request->session()->flash('msg_success', 'حذف گروه با موفقیت انجام شد');
        
        return redirect('/activity_groups');
    }

    public function reward(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $activity = ResidentActivityGroup::find($id);
        if(!$activity) {
            $request->session()->flash('msg_danger', 'گروه مورد نظر پیدا نشد');
            return redirect('/activity_groups');
        }

        $residents = ResidentActivityGroupJoin::where('resident_activity_groups_id', $id)->get();
        foreach($residents as $resident) {
            $theResident = Resident::find($resident->resident_id);
            if($theResident) {
                $theResident->residet_experience += $activity->exp;
                $theResident->coin += $activity->coin;
                $theResident->save();
            }
        }

        $activity->status = 'rewarded';
        $activity->save();
        $request->session()->flash('msg_success', 'اتمام گروه با موفقیت انجام شد');
        
        return redirect('/activity_groups');
    }
}
