<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\UserLevel;
use App\Users;

class UserLevelController extends Controller
{
    public function index(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=1) {
            return redirect('/');
        }
        $icons = [
            "success"=>"check",
            "danger"=>"ban",
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

        $loggedUser = Auth::user();
        $appUser = Users::where('email', $loggedUser->email)->first();
        $levels = [];
        if(!$appUser) {
            $msgs[] = [
                "msg"=>'کاربری شما ایراد دارد با پشتیبانی تماس بگیرید',
                "type"=>'danger',
                "icon"=>$icons['danger'],
            ];
        }else {
            $levels = UserLevel::where('user_id', $appUser->id)->get();
        }

        return view('user_levels.index', [
            "levels"=>$levels,
            "msgs"=>$msgs,
        ]);
    }

    public function create(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=1) {
            return redirect('/');
        }
        $level = new UserLevel;
        $loggedUser = Auth::user();
        $appUser = Users::where('email', $loggedUser->email)->first();
        if(!$appUser) {
            $request->session()->flash('msg_danger', 'کاربری شما ایراد دارد با پشتیبانی تماس بگیرید');
            return redirect('/userlevels');
        }
        
        if(!$request->isMethod('post')) {
            return view('user_levels.create', [
                "level"=>$level
            ]);
        }

        $level->name = $request->input('name');
        $level->min_exp = $request->input('min_exp');
        $level->max_exp = $request->input('max_exp');
        $level->user_id = $appUser->id;

        $level->save();
        
        $request->session()->flash('msg_success', 'سطح مورد نظر با موفقیت ثبت شد');
        return redirect('/userlevels');
    }

    public function edit(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=1) {
            return redirect('/');
        }
        $level = UserLevel::find($id);
        if(!$level) {
            $request->session()->flash('msg_danger', 'سطح مورد نظر پیدا نشد');
            return redirect('/userlevels');
        }

        if(!$request->isMethod('post')) {
            return view('user_levels.create', [
                "level"=>$level
            ]);
        }

        $level->name = $request->input('name');
        $level->min_exp = $request->input('min_exp');
        $level->max_exp = $request->input('max_exp');

        $level->save();
        
        $request->session()->flash('msg_success', 'سطح مورد نظر با موفقیت بروز شد');
        return redirect('/userlevels');
    }

    public function delete(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=1) {
            return redirect('/');
        }
        $level = UserLevel::find($id);
        if(!$level) {
            $request->session()->flash('msg_danger', 'سطح مورد نظر پیدا نشد');
            return redirect('/userlevels');
        }

        $level->delete();
        $request->session()->flash('msg_success', 'حذف سطح با موفقیت انجام شد');
        
        return redirect('/userlevels');
    }
}
