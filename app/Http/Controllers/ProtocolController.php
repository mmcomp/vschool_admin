<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Protocol;

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
        
        $protocols = Protocol::with('employer_agent')->with('contractor_agent')->with('type')
            ->with('employer')->with('contractor')->with('docs')->get();

        return view('home.admin', [
            "msgs"=>$msgs,
            "protocols"=>$protocols,
        ]);
    }
}
