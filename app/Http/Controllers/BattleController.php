<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\CalendarUtils;

use App\Http\Controllers\ActivityController;

use App\Sequence;
use App\Resident;
use App\Battle;

class BattleController extends Controller
{
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

        $battles = Battle::all();
        foreach($battles as $i=>$battle) {
            $battles[$i]->start_date = ActivityController::g2j($battle->start_date);
            $battles[$i]->end_date = ActivityController::g2j($battle->end_date);
        }

        return view('battle.index', [
            "battles"=>$battles,
            "msgs"=>$msgs,
        ]);
    }

    public function create(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $battle = new Battle;

        if(!$request->isMethod('post')) {
            $sequences = Sequence::all();
            return view('battle.create', [
                "battle"=>$battle,
                "sequences"=>$sequences,
            ]);
        }

        $battle->name = $request->input('name');
        $battle->description = $request->input('description');
        $battle->end_date = ActivityController::j2g($request->input('end_date'));
        $battle->sequences_id = (int)$request->input('sequences_id');
        $battle->start_date = ActivityController::j2g($request->input('start_date'));
        $battle->image_path = '';
        if($request->hasFile('image_path') && $request->file('image_path')->isValid()) {
            $image_path = 'battle_' . strtotime(date("Y-m-d H:i:s")) . '.' . $request->image_path->getClientOriginalExtension();
            $request->image_path->move('battles/' ,  $image_path);
            $battle->image_path = '/battles/' . $image_path;
        }

        $battle->save();
        
        $request->session()->flash('msg_success', 'نبرد مورد نظر با موفقیت ثبت شد');
        return redirect('/battle');
    }

    public function edit(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $battle = Battle::find($id);
        if(!$battle) {
            $request->session()->flash('msg_danger', 'نبرد مورد نظر پیدا نشد');
            return redirect('/battle');
        }

        $battle->start_date = ActivityController::g2j($battle->start_date);
        $battle->end_date = ActivityController::g2j($battle->end_date);

        if(!$request->isMethod('post')) {
            $sequences = Sequence::all();
            return view('battle.create', [
                "battle"=>$battle,
                "sequences"=>$sequences,
            ]);
        }

        $battle->name = $request->input('name');
        $battle->description = $request->input('description');
        $battle->end_date = ActivityController::j2g($request->input('end_date'));
        $battle->sequences_id = (int)$request->input('sequences_id');
        $battle->start_date = ActivityController::j2g($request->input('start_date'));
        if($request->hasFile('image_path') && $request->file('image_path')->isValid()) {
            $image_path = 'battle_' . strtotime(date("Y-m-d H:i:s")) . '.' . $request->image_path->getClientOriginalExtension();
            $request->image_path->move('battles/' ,  $image_path);
            $battle->image_path = '/battles/' . $image_path;
        }

        $battle->save();
        
        $request->session()->flash('msg_success', 'نبرد مورد نظر با موفقیت بروز شد');
        return redirect('/battle');
    }

    public function delete(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $battle = Battle::find($id);
        if(!$battle) {
            $request->session()->flash('msg_danger', 'نبرد مورد نظر پیدا نشد');
            return redirect('/battle');
        }

        $battle->delete();
        $request->session()->flash('msg_success', 'حذف نبرد با موفقیت انجام شد');
        
        return redirect('/battle');
    }
}
