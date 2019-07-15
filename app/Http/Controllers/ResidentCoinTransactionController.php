<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Resident;
use App\ResidentCoinTransaction;
use App\Users;
use App\Sequence;
use App\ResidentNickname;
use App\ResidentSign;


class ResidentCoinTransactionController extends Controller
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
        $residentCoinTrans = [];
        $resident = Resident::where('mobile', $loggedUser->email)->first();
        if(!$resident) {
            $msgs[] = [
                "msg"=>'شهروند پیدا نشد',
                "type"=>'danger',
                "icon"=>'ban',
            ];
        }else {
            $residentCoinTrans = ResidentCoinTransaction::where('resident_id', $resident->id)->orderBy('created_at', 'desc')->get();
            foreach($residentCoinTrans as $i=>$rs) {
                $process_name = '';
                if($rs->process_type=='user') {
                    $user = Users::find($rs->process_id);
                    if($user) {
                        $process_name = 'نرم افزار [' . $user->name . ']';
                    }
                }else if($rs->process_type=='sequence') {
                    $sequence = Sequence::find($rs->process_id);
                    if($sequence) {
                        $process_name = 'چرخه [' . $sequence->name . ']';
                    }
                }else if($rs->process_type=='nickname') {
                    $nickname = ResidentNickname::find($rs->process_id);
                    if($nickname) {
                        $process_name = 'لقب [' . $nickname->name . ']';
                    }
                }else if($rs->process_type=='sign') {
                    $sign = ResidentNickname::find($rs->process_id);
                    if($sign) {
                        $process_name = 'نشان [' . $sign->name . ']';
                    }
                }
                $residentCoinTrans[$i]['process_name'] = $process_name;
            }
        }

        return view('resident_coin_trans.index', [
            "residentCoinTrans"=>$residentCoinTrans,
            "msgs"=>$msgs,
        ]);
    }
}
