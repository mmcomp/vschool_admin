<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\CalendarUtils;

use App\Resident;
use App\ResidentSign;
use App\ResidentSignRelation;
use App\ResidentSignProgress;

class ResidentSignController extends Controller
{
    public function index(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=2) {
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
        $residentSigns = [];
        $resident = Resident::where('mobile', $loggedUser->email)->first();
        if(!$resident) {
            $msgs[] = [
                "msg"=>'شهروند پیدا نشد',
                "type"=>'danger',
                "icon"=>'ban',
            ];
        }else {
            $progressSignIds = [];
            $residentSigns = ResidentSignRelation::where('residents_id', $resident->id)->with('sign')->get();
            foreach($residentSigns as $i=>$rs) {
                $progressSignIds[] = $rs->resident_signs_id;
                $dateTmp = explode(' ', $rs->sign->start_date);
                $dateTmp = explode('-', $dateTmp[0]);
                $dateTmp = CalendarUtils::toJalali((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
                $residentSigns[$i]->sign->pstart_date = $dateTmp[0] . '/' . $dateTmp[1] . '/' . $dateTmp[2];
                $dateTmp = explode(' ', $rs->sign->end_date);
                $dateTmp = explode('-', $dateTmp[0]);
                $dateTmp = CalendarUtils::toJalali((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
                $residentSigns[$i]->sign->pend_date = $dateTmp[0] . '/' . $dateTmp[1] . '/' . $dateTmp[2];
            }
            $residentSignProgresses = ResidentSignProgress::where('resident_id', $resident->id)->with('sign')->get();
            foreach($residentSignProgresses as $i=>$rs) {
                $progressSignIds[] = $rs->resident_signs_id;
                $dateTmp = explode(' ', $rs->sign->start_date);
                $dateTmp = explode('-', $dateTmp[0]);
                $dateTmp = CalendarUtils::toJalali((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
                $residentSignProgresses[$i]->sign->pstart_date = $dateTmp[0] . '/' . $dateTmp[1] . '/' . $dateTmp[2];
                $dateTmp = explode(' ', $rs->sign->end_date);
                $dateTmp = explode('-', $dateTmp[0]);
                $dateTmp = CalendarUtils::toJalali((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
                $residentSignProgresses[$i]->sign->pend_date = $dateTmp[0] . '/' . $dateTmp[1] . '/' . $dateTmp[2];
            }
            $residentOtherSigns = ResidentSign::whereNotIn('id', $progressSignIds)->get();
            foreach($residentOtherSigns as $i=>$rs) {
                $dateTmp = explode(' ', $rs->start_date);
                $dateTmp = explode('-', $dateTmp[0]);
                $dateTmp = CalendarUtils::toJalali((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
                $residentOtherSigns[$i]->pstart_date = $dateTmp[0] . '/' . $dateTmp[1] . '/' . $dateTmp[2];
                $dateTmp = explode(' ', $rs->end_date);
                $dateTmp = explode('-', $dateTmp[0]);
                $dateTmp = CalendarUtils::toJalali((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
                $residentOtherSigns[$i]->pend_date = $dateTmp[0] . '/' . $dateTmp[1] . '/' . $dateTmp[2];
            }
        }
        return view('resident_signs.index', [
            "residentSigns"=>$residentSigns,
            "residentSignProgresses"=>$residentSignProgresses,
            "residentOtherSigns"=>$residentOtherSigns,
            "msgs"=>$msgs,
        ]);
    }
}
