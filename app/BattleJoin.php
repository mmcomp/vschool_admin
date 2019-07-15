<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Battle;
use App\Resident;
use App\Sequence;
use App\ResidentPropertyValue;

class BattleJoin extends Model
{
    public function battle() {
        return $this->belongsTo('App\Battle', 'battles_id');
    }

    public function fighterone() {
        return $this->belongsTo('App\Resident', 'fighter_one');
    }

    public function fightertwo() {
        return $this->belongsTo('App\Resident', 'fighter_two');
    }

    public static function SetWinners() {
        $readyToWinBattles = Battle::where('end_date', '<', date('Y-m-d 00:00:00'))->pluck('id')->toArray();
        $readyToWins = self::whereIn('battles_id', $readyToWinBattles)->with('battle')->where('winner_id', 0)->get();
        foreach($readyToWins as $readyToWin) {
            if($readyToWin->fighter_one>0 && $readyToWin->fighter_two>0 && $readyToWin->fighter_two!=$readyToWin->fighter_two_count) {
                $winner_id = $readyToWin->fighter_one;
                if($readyToWin->fighter_one_count<$readyToWin->fighter_two_count) {
                    $winner_id = $readyToWin->fighter_two;
                }
                $readyToWin->winner_id = $winner_id;
                $readyToWin->save();
                $resident = Resident::find($winner_id);
                if($resident) {
                    $sequence = Sequence::find($readyToWin->battle->sequences_id);
                    if($sequence) {
                        $resident->coin += $sequence->coin;
                        $resident->residet_experience += $sequence->exp_score;
                        $resident->save();

                        $residentPropertyValue = ResidentPropertyValue::where('resident_id', $resident->id)->where('redient_property_fields_id', $sequence->resident_property_fields_id)->first();
                        if(!$residentPropertyValue) {
                            $residentPropertyValue = new ResidentPropertyValue;
                            $residentPropertyValue->resident_id = $resident->id;
                            $residentPropertyValue->redient_property_fields_id = $sequence->resident_property_fields_id;
                            $residentPropertyValue->value = 0;
                        }
                        $residentPropertyValue->value += $sequence->resident_property_fields_value;
                        $residentPropertyValue->save();
                    }
                }
            }
        }
    }
}
