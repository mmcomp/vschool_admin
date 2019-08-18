<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Agent;
use App\Protocol;
use App\ProtocolType;
use App\Service;
use App\ServicesDesc;
use App\Unit;
use App\GiveWay;
use App\Province;
use App\City;
use App\Transaction;
use App\WinnerSelectWay;
use App\Company;
use App\FormalityStatus;
use App\FormalityType;
use App\Ownership;

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
        return view('protocol.index', [
            "msgs"=>$msgs,
            "protocols"=>$protocols,
            "protocolTypes"=>$protocolTypes,
            "req"=>$request->all(),
        ]);
    }

    public function create(Request $request) {
        $protocol = new Protocol;
        $services = Service::all();
        $services_descs = ServicesDesc::all();
        $units = Unit::all();
        $give_ways = GiveWay::all();
        $cities = City::all();
        $provinces = Province::all();
        $transactions = Transaction::all();
        $winner_select_ways = WinnerSelectWay::all();
        $companies = Company::where('id', '>', 0)->with(['ceo', 'city.province', 'service', 'ownership'])->get();
        $ownerships = Ownership::all();
        $agents = Agent::all();
        // dump($companies->toArray());
        $protocol_types = ProtocolType::all();
        $formality_statuses = FormalityStatus::all();
        $formality_types = FormalityType::all();
        if(!$request->isMethod('post')) {
            return view('protocol.create', [
                "services"=>$services,
                "services_descs"=>$services_descs,
                "units"=>$units,
                "give_ways"=>$give_ways,
                "provinces"=>$provinces,
                "cities"=>$cities,
                "transactions"=>$transactions,
                "winner_select_ways"=>$winner_select_ways,
                "companies"=>$companies,
                "protocol_types"=>$protocol_types,
                "formality_statuses"=>$formality_statuses,
                "formality_types"=>$formality_types,
                "ownerships"=>$ownerships,
                "agents"=>$agents
            ]);
        }

        if(!$request->file_path) {
            $request->session()->flash('msg_danger', 'ارسال مدرک الزامی است');
            return redirect('/protocol');
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
        return redirect('/protocol');
    }
}
