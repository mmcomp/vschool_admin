<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\UserCatagoryRestriction;
use App\Resident;
use App\ResidentCatagoryRelation;

use JWTAuth;

class Users extends Model
{
    protected $table = 'user';

    public function getToken() {
        $credentials = [
            "email"=>$this->email,
            "password"=>$this->password,
        ];
        $token = null;
        try {
            $token = JWTAuth::attempt($credentials);
        } catch (JWTException $e) {
            $token = '';
        }
        return $token;
    }

    public function checkResident($resident_id) {
        $out = false;
        $resident = Resident::find($resident_id);
        if($resident) {
            $out = true;
            $userCatagoryRestrictions = UserCatagoryRestriction::where('user_id', $this->id)->get();
            if(count($userCatagoryRestrictions)==0) {
                return $out;
            }
            $residentCatagoryRelations = ResidentCatagoryRelation::where('resident_id', $resident_id)->pluck('resident_catagory_id')->toArray();
            $out = false;
            $userCatagoryRestrictions = UserCatagoryRestriction::where('user_id', $this->id)->where('min_exp', '<', $resident->residet_experience)->whereIn('resident_catagories_id', $residentCatagoryRelations)->get();
            if(count($userCatagoryRestrictions)==0) {
                return $out;
            }
            $out = true;        
        }
        return $out;
    }
}
