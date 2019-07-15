<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Sequence;
use App\SequenceDetail;
use App\Users;
use App\ResidentPropertyField;
use App\ResidentCatagory;
use App\SequenceResidentCatagory;
use App\ResidentSequence;

class SequenceController extends Controller
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
        $sequences = Sequence::all();
        $theSeqs = [];
        foreach($sequences as $sequence) {
            $count = ResidentSequence::where('sequences_id', $sequence->id)->where('is_done', 1)->count();
            if(!$count) {
                $count = 0;
            }
            $sequence->count = $count;
            $theSeqs[] = $sequence;
        }

        return view('sequences.index', [
            "sequences"=>$theSeqs,
            "msgs"=>$msgs,
        ]);
    }

    public function create(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $sequence = new Sequence;
        $residentPropertyFields = ResidentPropertyField::all();
        $residentCatagories = ResidentCatagory::all();
        if(!$request->isMethod('post')) {
            return view('sequences.create', [
                "sequence"=>$sequence,
                "residentPropertyFields"=>$residentPropertyFields,
                "residentCatagories"=>$residentCatagories,
                "sequenceResidentCatagories"=>[],
            ]);
        }

        $sequence->name = $request->input('name');
        $sequence->start_time = $request->input('start_time');
        $sequence->end_time = $request->input('end_time');
        $sequence->status = $request->input('status');
        $sequence->exp_score = (float)$request->input('exp_score');
        $sequence->coin = (float)$request->input('coin');
        $sequence->resident_property_fields_id = (int)$request->input('resident_property_fields_id');
        $sequence->resident_property_fields_value = (float)$request->input('resident_property_fields_value');
        $sequence->admin_id = $loggedUser->id;

        $sequence->save();

        $catagories = $request->input('residentCatagory', []);
        foreach($catagories as $catagory) {
            $sequenceResidentCatagory = new SequenceResidentCatagory;
            $sequenceResidentCatagory->sequences_id = $sequence->id;
            $sequenceResidentCatagory->resident_catagory_id = $catagory;
            $sequenceResidentCatagory->save();
        }
        
        $request->session()->flash('msg_success', 'چرخه مورد نظر با موفقیت ثبت شد');
        return redirect('/sequences');
    }

    public function edit(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $sequence = Sequence::find($id);
        if(!$sequence) {
            $request->session()->flash('msg_danger', 'چرخه مورد نظر پیدا نشد');
            return redirect('/sequences');
        }
        $residentPropertyFields = ResidentPropertyField::all();
        $residentCatagories = ResidentCatagory::all();
        $sequenceResidentCatagories = SequenceResidentCatagory::where('sequences_id', $id)->get();
        $cats = [];
        foreach($sequenceResidentCatagories as $seqCat) {
            $cats[] = $seqCat->resident_catagory_id;
        }
        if(!$request->isMethod('post')) {
            return view('sequences.create', [
                "sequence"=>$sequence,
                "residentPropertyFields"=>$residentPropertyFields,
                "residentCatagories"=>$residentCatagories,
                "sequenceResidentCatagories"=>$cats,
            ]);
        }

        $sequence->name = $request->input('name');
        $sequence->start_time = $request->input('start_time');
        $sequence->end_time = $request->input('end_time');
        $sequence->status = $request->input('status');
        $sequence->exp_score = (float)$request->input('exp_score');
        $sequence->coin = (float)$request->input('coin');
        $sequence->resident_property_fields_id = (int)$request->input('resident_property_fields_id');
        $sequence->resident_property_fields_value = (float)$request->input('resident_property_fields_value');
        $sequence->admin_id = $loggedUser->id;

        $sequence->save();
        
        SequenceResidentCatagory::where('sequences_id', $sequence->id)->delete();
        $catagories = $request->input('residentCatagory', []);
        foreach($catagories as $catagory) {
            $sequenceResidentCatagory = new SequenceResidentCatagory;
            $sequenceResidentCatagory->sequences_id = $sequence->id;
            $sequenceResidentCatagory->resident_catagory_id = $catagory;
            $sequenceResidentCatagory->save();
        }

        $request->session()->flash('msg_success', 'چرخه مورد نظر با موفقیت ویرایش شد');
        return redirect('/sequences');
    }

    public function delete(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $sequence = Sequence::find($id);
        if(!$sequence) {
            $request->session()->flash('msg_danger', 'چرخه مورد نظر پیدا نشد');
            return redirect('/sequences');
        }

        $sequence->delete();
        $request->session()->flash('msg_success', 'حذف چرخه با موفقیت انجام شد');
        
        return redirect('/sequences');
    }

    public function detail(Request $request, $id) {
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

        $sequence = Sequence::find($id);
        if(!$sequence) {
            $request->session()->flash('msg_danger', 'چرخه مورد نظر پیدا نشد');
            return redirect('/sequences');
        }

        $sequenceDetails = SequenceDetail::where('sequences_id', $id)->with('user')->orderBy('seq_order')->get();

        return view('sequence_details.index', [
            "sequenceDetails"=>$sequenceDetails,
            "sequence"=>$sequence,
            "msgs"=>$msgs,
        ]);
    }

    public function detailCreate(Request $request, $sequence_id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $sequenceDetail = new SequenceDetail;

        $currentSequenceDetails = SequenceDetail::where('sequences_id', $sequence_id)->get();

        $users = Users::all();
        if(!$request->isMethod('post')) {
            return view('sequence_details.create', [
                "sequenceDetail"=>$sequenceDetail,
                "users"=>$users,
            ]);
        }

        $sequenceDetail->user_id = $request->input('user_id');
        $sequenceDetail->seq_order = (int)$request->input('seq_order');
        $sequenceDetail->sequences_id = $sequence_id;

        $sequenceDetail->save();
        
        $request->session()->flash('msg_success', 'جزئیات چرخه مورد نظر با موفقیت ثبت شد');
        return redirect('/sequences/details/' . $sequence_id);
    }

    public function detailEdit(Request $request, $id, $sequence_id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $sequenceDetail = SequenceDetail::find($id);
        if(!$sequenceDetail) {
            $request->session()->flash('msg_danger', 'جزئیات چرخه مورد نظر پیدا نشد');
            return redirect('/sequences/details/' . $sequence_id);
        }

        $users = Users::all();

        if(!$request->isMethod('post')) {
            return view('sequence_details.create', [
                "sequenceDetail"=>$sequenceDetail,
                "users"=>$users,
            ]);
        }

        $sequenceDetail->user_id = $request->input('user_id');
        $sequenceDetail->seq_order = (int)$request->input('seq_order');

        $sequenceDetail->save();
        
        $request->session()->flash('msg_success', 'جزئیات چرخه مورد نظر با موفقیت ثبت شد');
        return redirect('/sequences/details/' . $sequence_id);
    }

    public function detailDelete(Request $request, $id, $sequence_id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $sequenceDetail = SequenceDetail::find($id);
        if(!$sequenceDetail) {
            $request->session()->flash('msg_danger', 'جزئیات چرخه مورد نظر پیدا نشد');
            return redirect('/sequences/details/' . $sequence_id);
        }

        $sequenceDetail->delete();

        $request->session()->flash('msg_success', 'جزئیات چرخه مورد نظر با موفقیت حذف شد');
        return redirect('/sequences/details/' . $sequence_id);
    }
}
