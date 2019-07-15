<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\CalendarUtils;

use App\UserMedalian;
use App\Users;

class UserMedalianController extends Controller
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
            $medalians = UserMedalian::where('user_id', $appUser->id)->get();
        }

        return view('user_medalians.index', [
            "medalians"=>$medalians,
            "msgs"=>$msgs,
        ]);
    }

    public function create(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=1) {
            return redirect('/');
        }
        $medalian = new UserMedalian;
        $loggedUser = Auth::user();
        $appUser = Users::where('email', $loggedUser->email)->first();
        if(!$appUser) {
            $request->session()->flash('msg_danger', 'کاربری شما ایراد دارد با پشتیبانی تماس بگیرید');
            return redirect('/userlevels');
        }
        
        if(!$request->isMethod('post')) {
            return view('user_medalians.create', [
                "medalian"=>$medalian
            ]);
        }

        $medalian->name = $request->input('name');
        $medalian->min_exp = $request->input('min_exp');
        $medalian->code = $request->input('code');
        $dateTmp = explode('/', $request->input('start_date'));
        if((int)$dateTmp[0]>(int)$dateTmp[2]) {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        }else {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[2], (int)$dateTmp[1], (int)$dateTmp[0]);
        }
        $medalian->start_date = $dateTmp[0] . '-' . $dateTmp[1] . '-' . $dateTmp[2] . ' 00:00:00';
        $dateTmp = explode('/', $request->input('end_date'));
        if((int)$dateTmp[0]>(int)$dateTmp[2]) {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        }else {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[2], (int)$dateTmp[1], (int)$dateTmp[0]);
        }
        $medalian->end_date = $dateTmp[0] . '-' . $dateTmp[1] . '-' . $dateTmp[2] . ' 00:00:00';
        $medalian->user_id = $appUser->id;

        $medalian->save();
        
        $request->session()->flash('msg_success', 'نشان مورد نظر با موفقیت ثبت شد');
        return redirect('/user_medalians');
    }

    public function edit(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=1) {
            return redirect('/');
        }
        $medalian = UserMedalian::find($id);
        if(!$medalian) {
            $request->session()->flash('msg_danger', 'نشان مورد نظر پیدا نشد');
            return redirect('/user_medalians');
        }
        $dateTmp = explode(' ', $medalian->start_date);
        $dateTmp = explode('-', $dateTmp[0]);
        $dateTmp = CalendarUtils::toJalali((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        $medalian->start_date = $dateTmp[0] . '/' . $dateTmp[1] . '/' . $dateTmp[2];
        $dateTmp = explode(' ', $medalian->end_date);
        $dateTmp = explode('-', $dateTmp[0]);
        $dateTmp = CalendarUtils::toJalali((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        $medalian->end_date = $dateTmp[0] . '/' . $dateTmp[1] . '/' . $dateTmp[2];
        if(!$request->isMethod('post')) {
            return view('user_medalians.create', [
                "medalian"=>$medalian
            ]);
        }

        $medalian->name = $request->input('name');
        $medalian->min_exp = $request->input('min_exp');
        $medalian->code = $request->input('code');
        if((int)$dateTmp[0]>(int)$dateTmp[2]) {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        }else {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[2], (int)$dateTmp[1], (int)$dateTmp[0]);
        }
        $medalian->start_date = $dateTmp[0] . '-' . $dateTmp[1] . '-' . $dateTmp[2] . ' 00:00:00';
        $dateTmp = explode('/', $request->input('end_date'));
        if((int)$dateTmp[0]>(int)$dateTmp[2]) {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        }else {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[2], (int)$dateTmp[1], (int)$dateTmp[0]);
        }
        $medalian->end_date = $dateTmp[0] . '-' . $dateTmp[1] . '-' . $dateTmp[2] . ' 00:00:00';

        $medalian->save();
        
        $request->session()->flash('msg_success', 'نشان مورد نظر با موفقیت بروز شد');
        return redirect('/user_medalians');
    }

    public function delete(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=1) {
            return redirect('/');
        }
        $medalian = UserMedalian::find($id);
        if(!$medalian) {
            $request->session()->flash('msg_danger', 'نشان مورد نظر پیدا نشد');
            return redirect('/user_medalians');
        }

        $medalian->delete();
        $request->session()->flash('msg_success', 'حذف نشان با موفقیت انجام شد');
        
        return redirect('/user_medalians');
    }
}
