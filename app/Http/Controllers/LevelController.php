<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Level;

class LevelController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();
        if($user->group_id!=0) {
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
        $levels = Level::all();
        
        return view('level.index', [
            "msgs"=>$msgs,
            "levels"=>$levels,
        ]);
    }

    public function create(Request $request) {
        $level = new Level;
        if(!$request->isMethod('post')) {
            return view('level.create', [
                "level"=>$level,
            ]);
        }

        $level->name = $request->input('name');
        $level->min_score = ((int)$request->input('min_score')<=0)?0:(int)$request->input('min_score');
        $level->max_score = ((int)$request->input('max_score')<=0)?0:(int)$request->input('max_score');
        $level->save();
        
        $request->session()->flash('msg_success', 'لول مورد نظر با موفقیت ثبت شد');
        return redirect('/level');
    }

    public function edit(Request $request, $id) {
        $level = Level::find($id);
        if(!$level) {
            $request->session()->flash('msg_danger', 'لول مورد نظر پیدا نشد');
            return redirect('/level');
        }
        if(!$request->isMethod('post')) {
            return view('level.create', [
                "level"=>$level,
            ]);
        }

        $level->name = $request->input('name');
        $level->min_score = ((int)$request->input('min_score')<=0)?0:(int)$request->input('min_score');
        $level->max_score = ((int)$request->input('max_score')<=0)?0:(int)$request->input('max_score');
        $level->save();
        
        $request->session()->flash('msg_success', 'لول مورد نظر با موفقیت بروز شد');
        return redirect('/level');
    }


    public function delete(Request $request, $id) {
        $level = Level::find($id);
        if(!$level) {
            $request->session()->flash('msg_danger', 'لول مورد نظر پیدا نشد');
            return redirect('/level');
        }

        $level->delete();
        
        $request->session()->flash('msg_success', 'لول مورد نظر با موفقیت حذف شد');
        return redirect('/level');
    }
}
