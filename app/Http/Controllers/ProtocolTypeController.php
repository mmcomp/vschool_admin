<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ProtocolType;

class ProtocolTypeController extends Controller
{
    public function index(Request $request) {
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
        
        $protocolTypes = ProtocolType::all();

        return view('protocol_type.index', [
            "msgs"=>$msgs,
            "protocol_types"=>$protocolTypes,
        ]);
    }

    public function create(Request $request) {
        $protocolType = new ProtocolType;
        if(!$request->isMethod('post')) {
            return view('protocol_type.create', [
                "protocol_type"=>$protocolType,
            ]);
        }

        $protocolType->name = $request->input('name');
        $protocolType->description = $request->input('description');

        $protocolType->save();
        
        $request->session()->flash('msg_success', 'نوع قرارداد مورد نظر با موفقیت ثبت شد');
        return redirect('/statistics_protocol_type');
    }

    public function edit(Request $request, $id) {
        $protocolType = ProtocolType::find($id);
        if(!$protocolType) {
            $request->session()->flash('msg_danger', 'نوع قرارداد مورد نظر پیدا نشد');
            return redirect('/statistics_protocol_type');
        }
        if(!$request->isMethod('post')) {
            return view('protocol_type.create', [
                "protocol_type"=>$protocolType,
            ]);
        }


        $protocolType->name = $request->input('name');
        $protocolType->description = $request->input('description');

        $protocolType->save();

        $request->session()->flash('msg_success', 'نوع قرارداد مورد نظر با موفقیت ویرایش شد');
        return redirect('/statistics_protocol_type');
    }

    public function delete(Request $request, $id) {
        $protocolType = ProtocolType::find($id);
        if(!$protocolType) {
            $request->session()->flash('msg_danger', 'نوع قرارداد مورد نظر پیدا نشد');
            return redirect('/statistics_protocol_type');
        }

        $protocolType->delete();
        $request->session()->flash('msg_success', 'حذف نوع قرارداد با موفقیت انجام شد');
        
        return redirect('/statistics_protocol_type');
    }
}
