<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Agent;
use App\Protocol;
use App\ProtocolType;
use App\ProtocolDoc;
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
        $isSearch = ($request->input('is_search')=='1');
        $companyAdd = $request->session()->get('company_add');
        $theCompany = null;
        if(!$isSearch) {
            $companies = Company::where('id', '>', 0)->with(['ceo', 'city.province', 'service', 'ownership'])->get();
        }else {
            $companyAdd = true;
            $ceosIds = [];
            if((trim($request->input('search_company_fname', ''))!='' || trim($request->input('search_company_lname', ''))!='')) {
                $ceos = Agent::where('fname', 'like', '%' . trim($request->input('search_company_fname', '')) . '%')->where('lname', 'like', '%' . trim($request->input('search_company_lname', '')) . '%')->get();
                foreach($ceos as $ceo) {
                    $ceosIds[] = $ceo->id;
                }
            }
            if(count($ceosIds)==0 && (trim($request->input('search_company_fname', ''))!='' || trim($request->input('search_company_lname', ''))!='')) {
                $companies = [];
            }else {
                $companies = Company::where(function($query) use ($request) {
                    if($request->input('search_company_name')) {
                        $query->where('name', 'like', '%' . trim($request->input('search_company_name')) . '%');
                    }
                })->where(function($query) use ($ceosIds) {
                    if(count($ceosIds)>0) {
                        $query->whereIn('ceo_agents_id', $ceosIds);
                    }
                })->with(['ceo', 'city.province', 'service', 'ownership'])->get();
            }
            if($request->input('company_edit_id')) {
                $theCompany = Company::find($request->input('company_edit_id'));
            }
        }
        $ownerships = Ownership::all();
        $agents = Agent::all();
        $protocol_types = ProtocolType::all();
        $formality_statuses = FormalityStatus::all();
        $formality_types = FormalityType::all();
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
        if(!$request->isMethod('post') || $isSearch) {
            return view('protocol.create', [
                "msgs"=>$msgs,
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
                "agents"=>$agents,
                "companyAdd"=>$companyAdd,
                "data"=>$request->all(),
                "theCompany"=>$theCompany
            ]);
        }

        $file_pathes = [];
        if($request->file_path) {
            $files = $request->file('file_path');
            foreach($files as $file_path) {
                $file_pathes[] = $file_path->store('contract_docs');
            }
        }
        $protocol = new Protocol;
        $changed = false;
        foreach($request->all() as $key=>$value) {
            if($key!='file_path' && $protocol->$key && $value) {
                $protocol->$key = $value;
                $changed = true;
            }
        }
        if($changed) {
            $protocol->save();
            foreach($file_pathes as $file_path) {
                $protocolDoc = new ProtocolDoc;
                $protocolDoc->protocols_id = $protocol->id;
                $protocolDoc->file_path;
                $protocolDoc->expire_date = $request->input('expire_date', null);
                $protocolDoc->description = $request->input('description', '');
                $protocolDoc->save();
            }
            $request->session()->flash('msg_success', 'قرارداد مورد نظر با موفقیت ثبت شد');
            return redirect('/protocols');
        }

        return redirect('/protocols/create');
    }
}
