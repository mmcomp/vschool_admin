<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CertificateType;

class CertificateTypeController extends Controller
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
        
        $protocolTypes = CertificateType::all();

        return view('certificate_type.index', [
            "msgs"=>$msgs,
            "protocol_types"=>$protocolTypes,
        ]);
    }

    public function create(Request $request) {
        $protocolType = new CertificateType;
        if(!$request->isMethod('post')) {
            return view('certificate_type.create', [
                "protocol_type"=>$protocolType,
            ]);
        }

        $protocolType->name = $request->input('name');
        $protocolType->description = $request->input('description');

        $protocolType->save();
        
        $request->session()->flash('msg_success', 'نوع مدرک مورد نظر با موفقیت ثبت شد');
        return redirect('/statistics_certificate_type');
    }

    public function edit(Request $request, $id) {
        $protocolType = CertificateType::find($id);
        if(!$protocolType) {
            $request->session()->flash('msg_danger', 'نوع قرارداد مورد نظر پیدا نشد');
            return redirect('/statistics_certificate_type');
        }
        if(!$request->isMethod('post')) {
            return view('certificate_type.create', [
                "protocol_type"=>$protocolType,
            ]);
        }


        $protocolType->name = $request->input('name');
        $protocolType->description = $request->input('description');

        $protocolType->save();

        $request->session()->flash('msg_success', 'نوع مدرک مورد نظر با موفقیت ویرایش شد');
        return redirect('/statistics_certificate_type');
    }

    public function delete(Request $request, $id) {
        $protocolType = CertificateType::find($id);
        if(!$protocolType) {
            $request->session()->flash('msg_danger', 'نوع مدرک مورد نظر پیدا نشد');
            return redirect('/statistics_certificate_type');
        }

        $protocolType->delete();
        $request->session()->flash('msg_success', 'حذف نوع مدرک با موفقیت انجام شد');
        
        return redirect('/statistics_certificate_type');
    }
}
