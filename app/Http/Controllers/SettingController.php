<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Setting;

class SettingController extends Controller
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
        $settings = Setting::all();
        
        return view('setting.index', [
            "msgs"=>$msgs,
            "settings"=>$settings,
        ]);
    }

    public function edit(Request $request, $id) {
        $setting = Setting::find($id);
        if(!$setting) {
            $request->session()->flash('msg_danger', 'تنظیمات مورد نظر پیدا نشد');
            return redirect('/setting');
        }
        if(!$request->isMethod('post')) {
            return view('setting.create', [
                "setting"=>$setting,
            ]);
        }

        $setting->value = $request->input('value');

        $setting->save();
        
        $request->session()->flash('msg_success', 'تنظیمات مورد نظر با موفقیت بروز شد');
        return redirect('/setting');
    }
}
