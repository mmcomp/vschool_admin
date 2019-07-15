<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\ResidentSign;
use App\Users;
use App\ResidentPropertyField;
use Morilog\Jalali\CalendarUtils;

class SignController extends Controller
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
        $signs = ResidentSign::all();
        foreach($signs as $sign) {
            $tmp = explode(' ', $sign->start_date);
            $tmp = explode('-', $tmp[0]);
            $sign->pstart_date = CalendarUtils::toJalali($tmp[0], $tmp[1], $tmp[2]);
            $tmp = explode(' ', $sign->end_date);
            $tmp = explode('-', $tmp[0]);
            $sign->pend_date = CalendarUtils::toJalali($tmp[0], $tmp[1], $tmp[2]);
        }
        return view('signs.index', [
            "signs"=>$signs,
            "msgs"=>$msgs,
        ]);
    }

    public function create(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $sign = new ResidentSign;
        $residentPropertyFields = ResidentPropertyField::all();
        if(!$request->isMethod('post')) {
            return view('signs.create', [
                "sign"=>$sign,
                "residentPropertyFields"=>$residentPropertyFields,
            ]);
        }

        $sign->name = $request->input('name');

        $tmp = explode(' ', $request->input('start_date'));
        $tmp = explode('/', $tmp[0]);
        if((int)$tmp[0]>(int)$tmp[2]) {
            $tmp = CalendarUtils::toGregorian($tmp[0], $tmp[1], $tmp[2]);
        }else {
            $tmp = CalendarUtils::toGregorian($tmp[2], $tmp[1], $tmp[0]);
        }
        $sign->start_date = $tmp[0] . '-' . $tmp[1] . '-' . $tmp[2];

        $tmp = explode(' ', $request->input('end_date'));
        $tmp = explode('/', $tmp[0]);
        if((int)$tmp[0]>(int)$tmp[2]) {
            $tmp = CalendarUtils::toGregorian($tmp[0], $tmp[1], $tmp[2]);
        }else {
            $tmp = CalendarUtils::toGregorian($tmp[2], $tmp[1], $tmp[0]);
        }
        $sign->end_date = $tmp[0] . '-' . $tmp[1] . '-' . $tmp[2];

        $sign->name = $request->input('name');
        $sign->resident_property_fields_id = $request->input('resident_property_fields_id');
        $sign->resident_property_fields_change = $request->input('resident_property_fields_change');
        $sign->exp_score_change = $request->input('exp_score_change');
        $sign->image_path = '';
        if($request->hasFile('image_path') && $request->file('image_path')->isValid()) {
            $image_path = 'sign_' . strtotime(date("Y-m-d H:i:s")) . '.' . $request->image_path->getClientOriginalExtension();
            $request->image_path->move('signs/' ,  $image_path);
            $sign->image_path = '/signs/' . $image_path;
        }

        $sign->save();
        
        $request->session()->flash('msg_success', 'نشان مورد نظر با موفقیت ثبت شد');
        return redirect('/sign');
    }

    public function edit(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $sign = ResidentSign::find($id);
        if(!$sign) {
            $request->session()->flash('msg_danger', 'نشان مورد نظر پیدا نشد');
            return redirect('/sign');
        }
        $tmp = explode(' ', $sign->start_date);
        $tmp = explode('-', $tmp[0]);
        $tmp = CalendarUtils::toJalali($tmp[0], $tmp[1], $tmp[2]);
        $sign->start_date = $tmp[0] . '/' . $tmp[1] . '/' . $tmp[2];
        $tmp = explode(' ', $sign->end_date);
        $tmp = explode('-', $tmp[0]);
        $tmp = CalendarUtils::toJalali($tmp[0], $tmp[1], $tmp[2]);
        $sign->end_date = $tmp[0] . '/' . $tmp[1] . '/' . $tmp[2];        $residentPropertyFields = ResidentPropertyField::all();
        if(!$request->isMethod('post')) {
            return view('signs.create', [
                "sign"=>$sign,
                "residentPropertyFields"=>$residentPropertyFields,
            ]);
        }


        $sign->name = $request->input('name');

        $tmp = explode(' ', $request->input('start_date'));
        $tmp = explode('/', $tmp[0]);
        if((int)$tmp[0]>(int)$tmp[2]) {
            $tmp = CalendarUtils::toGregorian($tmp[0], $tmp[1], $tmp[2]);
        }else {
            $tmp = CalendarUtils::toGregorian($tmp[2], $tmp[1], $tmp[0]);
        }
        $sign->start_date = $tmp[0] . '-' . $tmp[1] . '-' . $tmp[2];

        $tmp = explode(' ', $request->input('end_date'));
        $tmp = explode('/', $tmp[0]);
        if((int)$tmp[0]>(int)$tmp[2]) {
            $tmp = CalendarUtils::toGregorian($tmp[0], $tmp[1], $tmp[2]);
        }else {
            $tmp = CalendarUtils::toGregorian($tmp[2], $tmp[1], $tmp[0]);
        }
        $sign->end_date = $tmp[0] . '-' . $tmp[1] . '-' . $tmp[2];

        $sign->name = $request->input('name');
        $sign->resident_property_fields_id = $request->input('resident_property_fields_id');
        $sign->resident_property_fields_change = $request->input('resident_property_fields_change');
        $sign->exp_score_change = $request->input('exp_score_change');

        if($request->hasFile('image_path') && $request->file('image_path')->isValid()) {
            $image_path = 'sign_' . strtotime(date("Y-m-d H:i:s")) . '.' . $request->image_path->getClientOriginalExtension();
            $request->image_path->move('signs/' ,  $image_path);
            $sign->image_path = '/signs/' . $image_path;
        }

        $sign->save();

        $request->session()->flash('msg_success', 'نشان مورد نظر با موفقیت ویرایش شد');
        return redirect('/sign');
    }

    public function delete(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $sign = ResidentSign::find($id);
        if(!$sign) {
            $request->session()->flash('msg_danger', 'نشان مورد نظر پیدا نشد');
            return redirect('/sign');
        }

        $sign->delete();
        $request->session()->flash('msg_success', 'حذف نشان با موفقیت انجام شد');
        
        return redirect('/sign');
    }
}
