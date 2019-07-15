<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\SequenceDetail;
use App\ResidentSequence;
use App\Tournamet;
use App\Resident;
use App\Tournament;
use App\TournamentJoin;
use App\Battle;
use App\BattleJoin;

class Sequence extends Model
{
    public static function checkResidentUser($resident_id, $user_id) {
        $checkedSequences = [];
        $tournaments = Tournament::where('limit_date', '<=', date('Y-m-d 23:59:59'))->get();
        $tournamentSequences = [];
        foreach($tournaments as $tournament) {
            $tournamentSequences[] = $tournament->sequences_id;
        }
        $battles = Battle::where('start_date', '>', date("Y-m-d 23:59:59"))->get();
        $battleSequences = [];
        foreach($battles as $battle) {
            $battleSequences[] = $battle->sequences_id;
        }

        $seqDets = SequenceDetail::where('user_id', $user_id)->with('sequence')/*->whereNotIn('sequences_id', $tournamentSequences)*/->get();
        foreach($seqDets as $seqDet) {
            if(!in_array($seqDet->sequence->id, $checkedSequences)){
                $checkedSequences[] = $seqDet->sequence->id;
                $startTime = strtotime(date("Y-m-d") . ' ' . $seqDet->sequence->start_time);
                $endTime = strtotime(date("Y-m-d") . ' ' . $seqDet->sequence->end_time);
                $now = strtotime(date("Y-m-d H:i:s"));
                if($now>=$startTime && $now<=$endTime) {
                    $sequenceLastOrder = SequenceDetail::where('sequences_id', $seqDet->sequences_id)->count();
                    $checkedSequences[] = $seqDet->sequences_id;
                    if($sequenceLastOrder && $sequenceLastOrder>0) {
                        $sequenceLastOrder--;

                        $userOrders = SequenceDetail::where('sequences_id', $seqDet->sequences_id)
                            ->where('user_id', $user_id)->orderBy('seq_order')->get();
                        $userSeqIds = [];
                        foreach($userOrders as $uO) {
                            $userSeqIds[] = $uO->id;
                        }
                        $foundOrder = false;
                        foreach($userSeqIds as $userSeqId) {
                            $userOrder = SequenceDetail::where('sequences_id', $seqDet->sequences_id)
                                ->where('id', '<', $userSeqId)
                                ->orderBy('seq_order')->count();
                            if(!$userOrder) {
                                $userOrder = 0;
                            }
                            $residentSequence = ResidentSequence::where('resident_id', $resident_id)
                                ->where('sequences_id', $seqDet->sequences_id)
                                ->where('created_at', 'like', date('Y-m-d') . '%')->first();
                            if($userOrder==0 && !$residentSequence) {
                                if(!$foundOrder) {
                                    $residentSequence = ResidentSequence::where('resident_id', $resident_id)
                                        ->where('sequences_id', $seqDet->sequences_id)
                                        ->where('sequence_order', 0)
                                        ->where('created_at', 'like', date('Y-m-d') . '%')->first();
                                    if(!$residentSequence) {
                                        $residentSequence = new ResidentSequence;
                                        $residentSequence->sequences_id = $seqDet->sequences_id;
                                        $residentSequence->resident_id = $resident_id;
                                        $residentSequence->sequence_order = 0;
                                        $residentSequence->save();
                                        $foundOrder = true;
                                    }
                                    
                                }
                            }else if($userOrder>0){
                                if(!$foundOrder) {
                                    $residentSequence = ResidentSequence::where('resident_id', $resident_id)
                                        ->where('sequences_id', $seqDet->sequences_id)
                                        ->where('sequence_order', $userOrder-1)
                                        ->where('created_at', 'like', date('Y-m-d') . '%')->first();
                                    if($residentSequence) {
                                        $residentSequence->sequence_order = $userOrder;
                                        if($userOrder==$sequenceLastOrder) {
                                            $residentSequence->is_done = 1;
                                            if(in_array($seqDet->sequences_id, $tournamentSequences)) {
                                                $tournament = Tournament::where('sequences_id', $seqDet->sequences_id)->where('limit_date', '<=', date('Y-m-d 23:59:59'))->first();
                                                if($tournament) {
                                                    $tournamentJoin = TournamentJoin::where('tournamets_id')->where('resident_id', $resident_id)->first();
                                                    if($tournamentJoin) {
                                                        $tournamentJoin->sequence_count++;
                                                        $tournamentJoin->save();
                                                        $residentSequence->delete();
                                                    }else {
                                                        $residentSequence->save();
                                                    }
                                                }else {
                                                    $residentSequence->save();
                                                }
                                            }else if(in_array($seqDet->sequences_id, $battleSequences)) {
                                                $battle = Battle::where('sequences_id', $seqDet->sequences_id)->where('start_date', '<=', date('Y-m-d 00:00:00'))->where('end_date', '>=', date('Y-m-d 00:00:00'))->first();
                                                if($battle) {
                                                    $battleJoin = BattleJoin::where('battles_id')->where(function ($query) use ($resident_id){
                                                        $query->where('fighter_one', $resident_id)->orWhere('fighter_two', $resident_id);
                                                    })->first();
                                                    if($battleJoin) {
                                                        if($battleJoin->fighter_one==$resident_id) {
                                                            $battleJoin->fighter_one_count++;
                                                        }else {
                                                            $battleJoin->fighter_two_count++;
                                                        }
                                                        $battleJoin->save();
                                                        $residentSequence->delete();
                                                    }else {
                                                        $residentSequence->save();
                                                    }
                                                }else {
                                                    $residentSequence->save();
                                                }
                                            }else {
                                                $resident = Resident::find($resident_id);
                                                if($resident) {
                                                    $resident->residet_experience += $seqDet->sequence->exp_score;
                                                    $resident->coin += $seqDet->sequence->coin;
                                                    $resident->save();
                                                }
                                                $residentSequence->save();
                                            }
                                        }else {
                                            $residentSequence->save();
                                        }
                                        $foundOrder = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
