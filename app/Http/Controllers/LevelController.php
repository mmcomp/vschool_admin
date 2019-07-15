<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Level;
use App\ResidentPropertyField;

class LevelController extends Controller
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
        $levels = Level::all();

        return view('levels.index', [
            "levels"=>$levels,
            "msgs"=>$msgs,
        ]);
    }

    public function create(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $level = new Level;
        $residentPropertyFields = ResidentPropertyField::all();
        if(!$request->isMethod('post')) {
            return view('levels.create', [
                "level"=>$level,
                "residentPropertyFields"=>$residentPropertyFields,
            ]);
        }

        $level->name = $request->input('name');
        $level->min_exp = (float)$request->input('min_exp');
        $level->max_exp = (float)$request->input('max_exp');
        $level->coin = (float)$request->input('coin');
        $level->resident_property_fields_id = $request->input('resident_property_fields_id');
        $level->resident_property_fields_value = (float)$request->input('resident_property_fields_value');

        $level->save();
        
        $request->session()->flash('msg_success', 'سطح مورد نظر با موفقیت ثبت شد');
        return redirect('/levels');
    }

    public function edit(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $level = Level::find($id);
        if(!$level) {
            $request->session()->flash('msg_danger', 'سطح مورد نظر پیدا نشد');
            return redirect('/levels');
        }
        $residentPropertyFields = ResidentPropertyField::all();
        if(!$request->isMethod('post')) {
            return view('levels.create', [
                "level"=>$level,
                "residentPropertyFields"=>$residentPropertyFields,
            ]);
        }

        $level->name = $request->input('name');
        $level->min_exp = $request->input('min_exp');
        $level->max_exp = $request->input('max_exp');
        $level->coin = (float)$request->input('coin');
        $level->resident_property_fields_id = $request->input('resident_property_fields_id');
        $level->resident_property_fields_value = (float)$request->input('resident_property_fields_value');

        $level->save();
        
        $request->session()->flash('msg_success', 'سطح مورد نظر با موفقیت بروز شد');
        return redirect('/levels');
    }

    public function delete(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $level = Level::find($id);
        if(!$level) {
            $request->session()->flash('msg_danger', 'سطح مورد نظر پیدا نشد');
            return redirect('/levels');
        }

        $level->delete();
        $request->session()->flash('msg_success', 'حذف سطح با موفقیت انجام شد');
        
        return redirect('/levels');
    }
}
