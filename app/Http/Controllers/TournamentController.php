<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\CalendarUtils;

use App\Http\Controllers\ActivityController;

use App\Sequence;
use App\Resident;
use App\Tournament;

class TournamentController extends Controller
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

        $tournaments = Tournament::all();
        foreach($tournaments as $i=>$tournament) {
            $tournaments[$i]->limit_date = ActivityController::g2j($tournament->limit_date);
        }

        return view('tournament.index', [
            "tournaments"=>$tournaments,
            "msgs"=>$msgs,
        ]);
    }

    public function create(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $tournament = new Tournament;

        if(!$request->isMethod('post')) {
            $sequences = Sequence::all();
            return view('tournament.create', [
                "tournament"=>$tournament,
                "sequences"=>$sequences,
            ]);
        }

        $tournament->name = $request->input('name');
        $tournament->description = $request->input('description');
        $tournament->max_count = (int)$request->input('max_count');
        $tournament->sequences_id = (int)$request->input('sequences_id');
        $tournament->limit_date = ActivityController::j2g($request->input('limit_date'));

        $tournament->save();
        
        $request->session()->flash('msg_success', 'چالش مورد نظر با موفقیت ثبت شد');
        return redirect('/tournamets');
    }

    public function edit(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $tournament = Tournament::find($id);
        if(!$tournament) {
            $request->session()->flash('msg_danger', 'چالش مورد نظر پیدا نشد');
            return redirect('/tournamets');
        }

        $tournament->limit_date = ActivityController::g2j($tournament->limit_date);

        if(!$request->isMethod('post')) {
            $sequences = Sequence::all();
            return view('tournament.create', [
                "tournament"=>$tournament,
                "sequences"=>$sequences,
            ]);
        }

        $tournament->name = $request->input('name');
        $tournament->description = $request->input('description');
        $tournament->max_count = (int)$request->input('max_count');
        $tournament->sequences_id = (int)$request->input('sequences_id');
        $tournament->limit_date = ActivityController::j2g($request->input('limit_date'));

        $tournament->save();
        
        $request->session()->flash('msg_success', 'چالش مورد نظر با موفقیت بروز شد');
        return redirect('/tournamets');
    }

    public function delete(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $tournament = Tournament::find($id);
        if(!$tournament) {
            $request->session()->flash('msg_danger', 'چالش مورد نظر پیدا نشد');
            return redirect('/tournamets');
        }

        $tournament->delete();
        $request->session()->flash('msg_success', 'حذف چالش با موفقیت انجام شد');
        
        return redirect('/tournamets');
    }
}
