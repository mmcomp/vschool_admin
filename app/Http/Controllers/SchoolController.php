<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\School;
use App\Zone;

class SchoolController extends Controller
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
        $schools = School::all();
        $schools->load(['zone', 'zone.city', 'zone.city.province']);
        
        return view('school.index', [
            "msgs"=>$msgs,
            "schools"=>$schools,
        ]);
    }

    public function create(Request $request) {
        $school = new School;
        $zones = Zone::all();
        if(!$request->isMethod('post')) {
            return view('school.create', [
                "school"=>$school,
                "zones"=>$zones
            ]);
        }

        $school->name = $request->input('name');
        $school->zones_id = $request->input('zones_id');
        $school->code = $request->input('code');
        $school->address = $request->input('address');
        $school->manager_name = $request->input('manager_name');
        $school->gender = $request->input('gender');
        $school->school_type = $request->input('school_type');
        $school->cycle = $request->input('cycle');
        $school->created_date = $request->input('created_date');
        $school->save();
        
        $request->session()->flash('msg_success', 'مدرسه مورد نظر با موفقیت ثبت شد');
        return redirect('/school');
    }

    public function edit(Request $request, $id) {
        $school = School::find($id);
        if(!$school) {
            $request->session()->flash('msg_danger', 'مدرسه مورد نظر پیدا نشد');
            return redirect('/school');
        }
        $zones = Zone::all();
        if(!$request->isMethod('post')) {
            return view('school.create', [
                "school"=>$school,
                "zones"=>$zones
            ]);
        }

        $school->name = $request->input('name');
        $school->zones_id = $request->input('zones_id');
        $school->code = $request->input('code');
        $school->address = $request->input('address');
        $school->manager_name = $request->input('manager_name');
        $school->gender = $request->input('gender');
        $school->school_type = $request->input('school_type');
        $school->cycle = $request->input('cycle');
        $school->created_date = $request->input('created_date');
        $school->save();
        
        $request->session()->flash('msg_success', 'مدرسه مورد نظر با موفقیت بروز شد');
        return redirect('/school');
    }


    public function delete(Request $request, $id) {
        $school = School::find($id);
        if(!$school) {
            $request->session()->flash('msg_danger', 'مدرسه مورد نظر پیدا نشد');
            return redirect('/school');
        }

        $school->delete();
        
        $request->session()->flash('msg_success', 'مدرسه مورد نظر با موفقیت حذف شد');
        return redirect('/school');
    }
}
