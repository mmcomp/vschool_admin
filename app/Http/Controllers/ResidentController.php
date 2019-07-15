<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ActivityController;

use App\Resident;
use App\ResidentCatagoryRelation;
use App\ResidentCatagory;
use App\ResidentActivityGroup;
use App\ResidentActivityGroupJoin;
use App\Tournament;
use App\TournamentJoin;
use App\Battle;
use App\BattleJoin;

class ResidentController extends Controller
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
        $resident = Resident::where('mobile', $loggedUser->email)->with('level')->with('catagory.catagory')
        ->with('property.propertyfield')->first();

        return view('resident.index', [
            "resident"=>$resident,
            "msgs"=>$msgs,
        ]);
    }

    public function catIndex(Request $request) {
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
        $resident = Resident::where('mobile', $loggedUser->email)->with('level')->with('catagory.catagory')
        ->with('property.propertyfield')->first();

        return view('resident_catagory.index', [
            "resident"=>$resident,
            "msgs"=>$msgs,
        ]);
    }

    public function catCreate(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=2) {
            return redirect('/');
        }
        $rcat = new ResidentCatagoryRelation;

        $resident = Resident::where('mobile', $loggedUser->email)->first();
        $ownCats = ResidentCatagoryRelation::where('resident_id', $resident->id)->get();
        $ownCatIds = [];
        foreach($ownCats as $ownCat) {
            $ownCatIds[] = $ownCat->resident_catagory_id;
        }

        $allcats = ResidentCatagory::all();//whereNotIn('id', $ownCatIds);
        // var_dump($cats);
        // die();
        $cats = [];
        foreach($allcats as $cat) {
            if(!in_array($cat->id, $ownCatIds)) {
                $cats[] = $cat;
            }
        }
        if(count($cats)==0) {
            $request->session()->flash('msg_danger', 'دسته ای برای افزودن پیدا نشد');
            return redirect('/resident_catagory');
        }
        if(!$request->isMethod('post')) {
            return view('resident_catagory.create', [
                "rcat"=>$rcat,
                "cats"=>$cats,
            ]);
        }

        $rcat->resident_id = $resident->id;
        $rcat->resident_catagory_id = $request->input('resident_catagory_id');

        $rcat->save();
        
        $request->session()->flash('msg_success', 'دسته مورد نظر با موفقیت ثبت شد');
        return redirect('/resident_catagory');
    }

    public function catEdit(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=2) {
            return redirect('/');
        }
        $rcat = ResidentCatagoryRelation::find($id);
        if(!$rcat) {
            $request->session()->flash('msg_danger', 'دسته مورد نظر پیدا نشد');
            return redirect('/resident_catagory');
        }
        $cats = ResidentCatagory::all();
        if(!$request->isMethod('post')) {
            return view('resident_catagory.create', [
                "rcat"=>$rcat,
                "cats"=>$cats,
            ]);
        }

        $rcat->resident_catagory_id = $request->input('resident_catagory_id');

        $rcat->save();
        
        $request->session()->flash('msg_success', 'دسته مورد نظر با موفقیت بروز شد');
        return redirect('/resident_catagory');
    }

    public function catDelete(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=2) {
            return redirect('/');
        }
        $rcat = ResidentCatagoryRelation::find($id);
        if(!$rcat) {
            $request->session()->flash('msg_danger', 'دسته مورد نظر پیدا نشد');
            return redirect('/resident_catagory');
        }

        $rcat->delete();
        $request->session()->flash('msg_success', 'حذف دسته مورد نظر با موفقیت انجام شد');
        
        return redirect('/resident_catagory');
    }

    public function leaderBoard(Request $request) {
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
        $resident = Resident::where('mobile', $loggedUser->email)->first();
        $tops = [];
        $lows = [];
        $topCount = 20;
        if($resident->show_in_leaderboard==1) {
            $tops = Resident::where('residet_experience', '>', 0)->orderBy('residet_experience', 'desc')->limit($topCount)->get();
            if(count($tops)==$topCount) {
                $found = false;
                foreach($tops as $atop) {
                    if($atop->id==$resident->id) {
                        $found = true;
                    }
                }
                if(!$found) {
                    $position = Resident::where('residet_experience', '>=', $resident->residet_experience)->where('id', '!=', $resident->id)->get();
                    $position = $position->count() + 1; 
                    $lowups = Resident::where('residet_experience', '>=', $resident->residet_experience)->where('id', '!=', $resident->id)->orderBy('residet_experience')->limit(5)->get();
                    $lowdowns = Resident::where('residet_experience', '<', $resident->residet_experience)->orderBy('residet_experience', 'desc')->limit(5)->get();

                    $resident->position = $position;
                    $lowers = [];
                    foreach($lowups as $i=>$lowup) {
                        $lowup->position = $position + $i + 1;
                        // array_unshift($lows, $lowup);
                        $lowers[] = $lowup;
                    }
                    // var_dump($lowers);
                    for($i = count($lowers)-1;$i>=0;$i--) {
                        $lows[] = $lowers[$i];
                    }
                    $lows[] = $resident;
                    // var_dump($lows);
                    // die();
                    foreach($lowdowns as $i=>$lowdown) {
                        $lowdown->position = $position - ($i + 1);
                        $lows[] = $lowdown;
                    }
                }
            }
        }

        return view('resident_leaderboard.index', [
            "resident"=>$resident,
            "tops"=>$tops,
            "lows"=>$lows,
            "msgs"=>$msgs,
        ]);
    }

    public function acceptPrivecy(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=2) {
            return redirect('/');
        }

        Resident::where('mobile', $loggedUser->email)->update([
            "show_in_leaderboard"=>1,
        ]);

        return redirect('/resident_leaderboard');
    }

    public function updateImage(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=2) {
            return redirect('/');
        }
        
        $show_in_leaderboard = 0;
        if($request->input('show_in_leaderboard')) {
            $show_in_leaderboard = 1;
        }

        if($request->hasFile('image_path') && $request->file('image_path')->isValid()) {
            $image_path = 'resident_' . strtotime(date("Y-m-d H:i:s")) . '.' . $request->image_path->getClientOriginalExtension();
            $request->image_path->move('resident_images/' ,  $image_path);

            Resident::where('mobile', $loggedUser->email)->update([
                "image_path"=>'/resident_images/' . $image_path,
                "show_in_leaderboard"=>$show_in_leaderboard,
            ]);
        }else {
            Resident::where('mobile', $loggedUser->email)->update([
                "show_in_leaderboard"=>$show_in_leaderboard,
            ]);
        }

        return redirect('/');
    }

    public function activityIndex(Request $request) {
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

        $resident = Resident::where('mobile', $loggedUser->email)->first();

        $activities = ResidentActivityGroup::whereRaw('current_count < max_count')->where(function ($query) {
            $query->whereNull('join_limit_date')->orWhere('join_limit_date', '>=', date('Y-m-d 00:00:00'));
        })->get();

        $ownActivities = ResidentActivityGroupJoin::where('resident_id', $resident->id)->get();

        foreach($activities as $i=>$activity) {
            $is_in = false;
            foreach($ownActivities as $ownActivity) {
                if($ownActivity->resident_activity_groups_id==$activity->id) {
                    $is_in = true;
                }
            }
            $activities[$i]->is_in = $is_in;
        }

        return view('resident_activity.index', [
            "activities"=>$activities,
            "msgs"=>$msgs,
        ]);
    }

    public function activityJoin(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=2) {
            return redirect('/');
        }

        $activity = ResidentActivityGroup::find($id);
        if(!$activity) {
            $request->session()->flash('msg_danger', 'گروه مورد نظر پیدا نشد');
            return redirect('/resident_activity');
        }

        $resident = Resident::where('mobile', $loggedUser->email)->first();

        if($activity->current_count>=$activity->max_count) {
            $request->session()->flash('msg_danger', 'گروه مورد نظر پر شده است');
            return redirect('/resident_activity');
        }

        $residentActivityGroupJoin = new ResidentActivityGroupJoin;
        $residentActivityGroupJoin->resident_activity_groups_id = $id;
        $residentActivityGroupJoin->resident_id = $resident->id;
        $residentActivityGroupJoin->save();

        $activity->current_count++;
        $activity->save();

        $request->session()->flash('msg_success', 'به گروه با موفقیت افزوده شدید');
        return redirect('/resident_activity');
    }

    public function tournamentIndex(Request $request) {
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

        $resident = Resident::where('mobile', $loggedUser->email)->first();

        $tournamets = Tournament::whereRaw('current_count < max_count')->where(function ($query) {
            $query->whereNull('limit_date')->orWhere('limit_date', '>=', date('Y-m-d 00:00:00'));
        })->get();

        $ownTournaments = TournamentJoin::where('resident_id', $resident->id)->get();

        foreach($tournamets as $i=>$tournamet) {
            $is_in = false;
            foreach($ownTournaments as $ownTournament) {
                if($ownTournament->tournamets_id==$tournamet->id) {
                    $is_in = true;
                }
            }
            $tournamets[$i]->is_in = $is_in;
        }

        return view('resident_tournament.index', [
            "tournaments"=>$tournamets,
            "msgs"=>$msgs,
        ]);
    }

    public function tournamentJoin(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=2) {
            return redirect('/');
        }

        $tournamet = Tournament::find($id);
        if(!$tournamet) {
            $request->session()->flash('msg_danger', 'چالش مورد نظر پیدا نشد');
            return redirect('/resident_tournament');
        }

        $resident = Resident::where('mobile', $loggedUser->email)->first();

        if($tournamet->current_count>=$tournamet->max_count) {
            $request->session()->flash('msg_danger', 'چالش مورد نظر پر شده است');
            return redirect('/resident_tournament');
        }

        $tournamentJoin = new TournamentJoin;
        $tournamentJoin->tournamets_id = $id;
        $tournamentJoin->resident_id = $resident->id;
        $tournamentJoin->save();

        $tournamet->current_count++;
        $tournamet->save();

        $request->session()->flash('msg_success', 'به چالش با موفقیت افزوده شدید');
        return redirect('/resident_tournament');
    }

    public function tournamentLeader(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=2) {
            return redirect('/');
        }

        $tournamet = Tournament::find($id);
        if(!$tournamet) {
            $request->session()->flash('msg_danger', 'چالش مورد نظر پیدا نشد');
            return redirect('/resident_tournament');
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
        $resident = Resident::where('mobile', $loggedUser->email)->first();
        $residentTournament = TournamentJoin::where('tournamets_id', $id)->where('resident_id', $resident->id)->first();
        if(!$residentTournament) {
            $request->session()->flash('msg_danger', 'چالش مورد نظر شامل شما نمی شود');
            return redirect('/resident_tournament');
        }

        $resident->score = $residentTournament->sequence_count;

        $topCount = 20;
        $lows = [];
        $tops = TournamentJoin::where('tournamets_id', $id)->with('resident')->orderBy('sequence_count', 'desc')->limit($topCount)->get();
        $found = false;
        foreach($tops as $i=>$atop) {
            if($atop->resident_id==$resident->id) {
                $found = true;
            }
            $atop->resident->score = $atop->sequence_count;
            $tops[$i] = $atop->resident;
        }
        if(count($tops)==$topCount) {
            if(!$found) {
                $position = TournamentJoin::where('sequence_count', '>=', $resident->score)->where('resident_id', '!=', $resident->id)->get();
                $position = $position->count() + 1; 
                $lowups = TournamentJoin::where('sequence_count', '>=', $resident->score)->with('resident')->where('resident_id', '!=', $resident->id)->orderBy('sequence_count')->limit(5)->get();
                $lowdowns = TournamentJoin::where('sequence_count', '<', $resident->score)->with('resident')->orderBy('sequence_count', 'desc')->limit(5)->get();

                $resident->position = $position;
                $lowers = [];
                foreach($lowups as $i=>$lowup) {
                    $lowup->resident->score = $lowup->sequence_count;
                    $lowup = $lowup->resident;
                    $lowup->position = $position + $i + 1;
                    $lowers[] = $lowup;
                }

                for($i = count($lowers)-1;$i>=0;$i--) {
                    $lows[] = $lowers[$i];
                }
                $lows[] = $resident;

                foreach($lowdowns as $i=>$lowdown) {
                    $lowdown->resident->score = $lowdown->sequence_count;
                    $lowdown = $lowdown->resident;
                    $lowdown->position = $position - ($i + 1);
                    $lows[] = $lowdown;
                }
            }
        }

        return view('resident_tournament.leader', [
            "tournament"=>$tournamet,
            "resident"=>$resident,
            "tops"=>$tops,
            "lows"=>$lows,
            "msgs"=>$msgs,
        ]);
    }

    public function battleIndex(Request $request) {
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

        $resident = Resident::where('mobile', $loggedUser->email)->first();

        BattleJoin::SetWinners();

        $battles = Battle::where('end_date', '>=', date("Y-m-d 00:00:00"))->get();

        $ownBattles = BattleJoin::where('fighter_one', $resident->id)->orWhere('fighter_two', $resident->id)->with('battle')->with('fighterone')->with('fightertwo')->get();

        $battleInIds = [];
        foreach($battles as $i=>$battle) {
            $is_in = false;
            $battles[$i]->you_count = 0;
            $battles[$i]->fighter_count = 0;
            foreach($ownBattles as $ownBattle) {
                if($ownBattle->battles_id==$battle->id) {
                    $is_in = true;
                    $battleInIds[] = $battle->id;
                    $battles[$i]->fighter = null;
                    if($resident->id==$ownBattle->fighter_one) {
                        $battles[$i]->fighter = $ownBattle->fightertwo;
                        $battles[$i]->you_count = $ownBattle->fighter_one_count;
                        $battles[$i]->fighter_count = $ownBattle->fighter_two_count;
                    }else {
                        $battles[$i]->fighter = $ownBattle->fighterone;
                        $battles[$i]->you_count = $ownBattle->fighter_two_count;
                        $battles[$i]->fighter_count = $ownBattle->fighter_one_count;
                    }
                }
            }
            $battles[$i]->is_in = $is_in;
            $battles[$i]->winner_id = 0;
            $battles[$i]->pend_date = ActivityController::g2j($battle->end_date);
        }

        foreach($ownBattles as $ownBattle) {
            if(!in_array($ownBattle->id, $battleInIds)) {
                $ownBattle->battle->is_in = true;
                $ownBattle->battle->winner_id = $ownBattle->winner_id;
                $battles[] = $ownBattle->battle;
            }
        }

        return view('resident_battle.index', [
            "battles"=>$battles,
            "resident"=>$resident,
            "msgs"=>$msgs,
        ]);
    }

    public function battleJoin(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=2) {
            return redirect('/');
        }

        $battle = Battle::find($id);
        if(!$battle) {
            $request->session()->flash('msg_danger', 'نبرد مورد نظر پیدا نشد');
            return redirect('/resident_battle');
        }

        $resident = Resident::where('mobile', $loggedUser->email)->first();


        $battleJoin = BattleJoin::where('battles_id', $battle->id)->where('fighter_one', '>', 0)->where('fighter_two', 0)->first();
        if(!$battleJoin) {
            $battleJoin = new BattleJoin;
            $battleJoin->battles_id = $id;
            $battleJoin->fighter_one = $resident->id;
            $battleJoin->save();
        }else {
            $battleJoin->fighter_two = $resident->id;
            $battleJoin->save();
        }

        $request->session()->flash('msg_success', 'به نبرد با موفقیت افزوده شدید');
        return redirect('/resident_battle');
    }
}
