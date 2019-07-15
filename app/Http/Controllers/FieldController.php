<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\ResidentPropertyField;

class FieldController extends Controller
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
        $fields = ResidentPropertyField::all();

        return view('fields.index', [
            "fields"=>$fields,
            "msgs"=>$msgs,
        ]);
    }

    public function create(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $field = new ResidentPropertyField;

        if(!$request->isMethod('post')) {
            return view('fields.create', [
                "field"=>$field
            ]);
        }

        $field->field_name = $request->input('field_name');
        $field->field_type = $request->input('field_type');
        $field->default_value = $request->input('default_value');

        $field->save();
        
        $request->session()->flash('msg_success', 'متغیر مورد نظر با موفقیت ثبت شد');
        return redirect('/fields');
    }

    public function edit(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $field = ResidentPropertyField::find($id);
        if(!$field) {
            $request->session()->flash('msg_danger', 'متغیر مورد نظر پیدا نشد');
            return redirect('/fields');
        }

        if(!$request->isMethod('post')) {
            return view('fields.create', [
                "field"=>$field
            ]);
        }

        $field->field_name = $request->input('field_name');
        $field->field_type = $request->input('field_type');
        $field->default_value = $request->input('default_value');

        $field->save();
        
        $request->session()->flash('msg_success', 'متغیر مورد نظر با موفقیت بروز شد');
        return redirect('/fields');
    }

    public function delete(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $field = ResidentPropertyField::find($id);
        if(!$field) {
            $request->session()->flash('msg_danger', 'متغیر مورد نظر پیدا نشد');
            return redirect('/fields');
        }

        $field->delete();
        $request->session()->flash('msg_success', 'حذف متغیر با موفقیت انجام شد');
        
        return redirect('/fields');
    }
}
