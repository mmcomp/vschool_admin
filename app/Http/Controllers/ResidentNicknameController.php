<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\ResidentNickname;
use App\ResidentPropertyField;

class ResidentNicknameController extends Controller
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
        $residentNicknames = ResidentNickname::where('id', '>', 0)->with('field')->get();

        return view('nicknames.index', [
            "residentNicknames"=>$residentNicknames,
            "msgs"=>$msgs,
        ]);
    }

    public function create(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $residentNickname = new ResidentNickname;
        $residentPropertyFields = ResidentPropertyField::all();
        if(!$request->isMethod('post')) {
            return view('nicknames.create', [
                "residentNickname"=>$residentNickname,
                "residentPropertyFields"=>$residentPropertyFields,
            ]);
        }

        $residentNickname->name = $request->input('name');
        $residentNickname->resident_property_fields_id = $request->input('resident_property_fields_id');
        $residentNickname->resident_property_fields_value = (float)$request->input('resident_property_fields_value');

        if($request->hasFile('image') && $request->image->isValid()) {
            $request->image->move(public_path() . '/nickname_icons/', $request->image->getClientOriginalName());
            $residentNickname->image = '/nickname_icons/' . $request->image->getClientOriginalName();
        }

        $residentNickname->save();
        
        $request->session()->flash('msg_success', 'لقب مورد نظر با موفقیت ثبت شد');
        return redirect('/nicknames');
    }

    public function edit(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $residentNickname = ResidentNickname::find($id);
        if(!$residentNickname) {
            $request->session()->flash('msg_danger', 'لقب مورد نظر پیدا نشد');
            return redirect('/nicknames');
        }
        $residentPropertyFields = ResidentPropertyField::all();
        if(!$request->isMethod('post')) {
            return view('nicknames.create', [
                "residentNickname"=>$residentNickname,
                "residentPropertyFields"=>$residentPropertyFields,
            ]);
        }

        $residentNickname->name = $request->input('name');
        $residentNickname->resident_property_fields_id = $request->input('resident_property_fields_id');
        $residentNickname->resident_property_fields_value = (float)$request->input('resident_property_fields_value');

        if($request->hasFile('image') && $request->image->isValid()) {
            $request->image->move(public_path() . '/nickname_icons/', $request->image->getClientOriginalName());
            $residentNickname->image = '/nickname_icons/' . $request->image->getClientOriginalName();
        }
        
        $residentNickname->save();
        
        $request->session()->flash('msg_success', 'لقب مورد نظر با موفقیت بروز شد');
        return redirect('/nicknames');
    }

    public function delete(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $residentNickname = ResidentNickname::find($id);
        if(!$residentNickname) {
            $request->session()->flash('msg_danger', 'لقب مورد نظر پیدا نشد');
            return redirect('/nickanmes');
        }

        $residentNickname->delete();
        $request->session()->flash('msg_success', 'حذف لقب با موفقیت انجام شد');
        
        return redirect('/nicknames');
    }
}
