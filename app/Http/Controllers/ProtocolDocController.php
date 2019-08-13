<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ProtocolDoc;
use App\Protocol;

class ProtocolDocController extends Controller
{
    public function theIndex(Request $request, $id) {
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
        $protocol = Protocol::find($id);
        if(!$protocol) {
            $request->session()->flash('msg_danger', ' قرارداد مورد نظر پیدا نشد');
            return redirect('/protocols');
        }
        $protocolDocs = ProtocolDoc::where('protocols_id', $id)->get();

        return view('protocol_doc.index', [
            "msgs"=>$msgs,
            "protocolDocs"=>$protocolDocs,
            "protocol"=>$protocol,
        ]);
    }

    public function create(Request $request, $id) {
        $protocolDoc = new ProtocolDoc;
        $protocol = Protocol::find($id);
        if(!$protocol) {
            $request->session()->flash('msg_danger', ' قرارداد مورد نظر پیدا نشد');
            return redirect('/protocols');
        }
        if(!$request->isMethod('post')) {
            return view('protocol_doc.create', [
                "protocolDoc"=>$protocolDoc,
                "protocol"=>$protocol,
            ]);
        }

        if(!$request->file_path) {
            $request->session()->flash('msg_danger', 'ارسال مدرک الزامی است');
            return redirect('/protocoldoc/' . $id);
        }
        $protocolDoc->description = $request->input('description');
        $protocolDoc->protocols_id = $id;
        $file = $request->file_path->store('docs');
        $protocolDoc->file_path = $file;
        if($request->input('expire_date') && trim($request->input('expire_date'))!='') {
            $protocolDoc->expire_date = $request->input('expire_date');
        }
        $protocolDoc->save();
        
        $request->session()->flash('msg_success', 'مدرک قرارداد مورد نظر با موفقیت ثبت شد');
        return redirect('/protocoldoc/' . $id);
    }

    public function edit(Request $request, $id) {
        $protocolDoc = ProtocolDoc::find($id);
        if(!$protocolDoc) {
            $request->session()->flash('msg_danger', 'مدرک قرارداد مورد نظر پیدا نشد');
            return redirect('/protocoldoc');
        }
        $protocol = Protocol::find($protocolDoc->protocols_id);
        if(!$request->isMethod('post')) {
            return view('protocol_doc.create', [
                "protocolDoc"=>$protocolDoc,
                "protocol"=>$protocol,
            ]);
        }


        $protocolDoc->description = $request->input('description');
        if($request->file_path) {
            $file = $request->file_path->store('docs');
            $protocolDoc->file_path = $file;
        }
        if($request->input('expire_date') && trim($request->input('expire_date'))!='') {
            $protocolDoc->expire_date = $request->input('expire_date');
        }else {
            $protocolDoc->expire_date = null;
        }
        $protocolDoc->save();
        
        $request->session()->flash('msg_success', 'مدرک قرارداد مورد نظر با موفقیت ویرایش شد');
        return redirect('/protocoldoc/' . $protocolDoc->protocols_id);
    }

    // public function delete(Request $request, $id) {
    //     $protocolType = ProtocolType::find($id);
    //     if(!$protocolType) {
    //         $request->session()->flash('msg_danger', 'نوع قرارداد مورد نظر پیدا نشد');
    //         return redirect('/statistics_protocol_type');
    //     }

    //     $protocolType->delete();
    //     $request->session()->flash('msg_success', 'حذف نوع قرارداد با موفقیت انجام شد');
        
    //     return redirect('/statistics_protocol_type');
    // }
}
