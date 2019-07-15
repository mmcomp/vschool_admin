<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\ResidentCatagory;

class ResidentCatagoryController extends Controller
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
        $rcats = ResidentCatagory::all();

        return view('resident_catagories.index', [
            "rcats"=>$rcats,
            "msgs"=>$msgs,
        ]);
    }

    public function create(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $rcat = new ResidentCatagory;

        if(!$request->isMethod('post')) {
            return view('resident_catagories.create', [
                "rcat"=>$rcat
            ]);
        }

        $rcat->name = $request->input('name');
        $rcat->description = $request->input('description');

        $rcat->save();
        
        $request->session()->flash('msg_success', 'دسته مورد نظر با موفقیت ثبت شد');
        return redirect('/resident_catagories');
    }

    public function edit(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $rcat = ResidentCatagory::find($id);
        if(!$rcat) {
            $request->session()->flash('msg_danger', 'دسته مورد نظر پیدا نشد');
            return redirect('/resident_catagories');
        }

        if(!$request->isMethod('post')) {
            return view('resident_catagories.create', [
                "rcat"=>$rcat
            ]);
        }

        $rcat->name = $request->input('name');
        $rcat->description = $request->input('description');

        $rcat->save();
        
        $request->session()->flash('msg_success', 'دسته مورد نظر با موفقیت بروز شد');
        return redirect('/resident_catagories');
    }

    public function delete(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $rcat = ResidentCatagory::find($id);
        if(!$rcat) {
            $request->session()->flash('msg_danger', 'دسته مورد نظر پیدا نشد');
            return redirect('/resident_catagories');
        }

        $rcat->delete();
        $request->session()->flash('msg_success', 'حذف دسته مورد نظر با موفقیت انجام شد');
        
        return redirect('/resident_catagories');
    }
}
