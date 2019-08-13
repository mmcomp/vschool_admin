<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Protocol;
use App\ProtocolType;

class ProtocolController extends Controller
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
        if($request->getMethod()!='GET'){
            $protocols = Protocol::with('employer_agent')->with('contractor_agent')->with('type')
                ->with('employer')->with('contractor')->with('docs')
                ->where(function($query) use ($request) {
                    if($request->input('title') && trim($request->input('title'))!='') {
                        $query->where('title', 'like', '%' . $request->input('title') . '%');
                    }
                    if($request->input('type') && trim($request->input('type'))!='') {
                        $query->where('protocol_types_id', $request->input('type'));
                    }
                })
                ->get();
        }else {
            $protocols = Protocol::with('employer_agent')->with('contractor_agent')->with('type')
                ->with('employer')->with('contractor')->with('docs')->get();
        }
        return view('home.admin', [
            "msgs"=>$msgs,
            "protocols"=>$protocols,
            "protocolTypes"=>$protocolTypes,
            "req"=>$request->all(),
        ]);
    }
}
