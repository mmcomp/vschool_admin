<?php

namespace App\Http\Controllers;
use Tymon\JWTAuth\Exceptions\JWTException;

use JWTAuth;

use Illuminate\Http\Request;
use App\Users;
use App\UserProperty;
use App\Resident;
use App\ResidentPropertyField;
use App\ResidentPropertyValue;
use App\ResidentUserUsage;
use App\ResidentUserExp;
use App\Sequence;

class ApplicationController extends Controller
{
    public function test(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $appUser = Users::where('email', $user->email)->first();
        // $token = JWTAuth::getToken();
        // $new_token = JWTAuth::refresh($token);
        $out = [
            "status"=>1,
            "messages"=>[],
            "data"=>[
                "test"=>"Salam",
            ]
        ];
        if(!$appUser) {
            $out['messages'][0]['code'] = "AppNotFound";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم ثبت نشده است";
            return $out;
        }
        if($appUser->status!='accepted') {
            $out['messages'][0]['code'] = "AppNotAccepted";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم تایید نشده است";
            return $out;
        }
        return $out;
    }

    public function updateResidentField(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $appUser = Users::where('email', $user->email)->first();
        $out = [
            "status"=>0,
            "messages"=>[
                [
                    "code"=>"UnknowError",
                    "message"=>"خطای نا مشخص",
                ],
            ],
        ];
        if(!$appUser) {
            $out['messages'][0]['code'] = "AppNotFound";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم ثبت نشده است";
            return $out;
        }
        if($appUser->status!='accepted') {
            $out['messages'][0]['code'] = "AppNotAccepted";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم تایید نشده است";
            return $out;
        }
        $mobile = $request->input('resident', '');
        $resident = Resident::where('mobile', $mobile)->first();
        if(!$resident) {
            $out['messages'][0]['code'] = "ResidentNotFound";
            $out['messages'][0]['message'] = "شهروندی با این شماره موبایل پیدا نشد";
            return $out;
        }
        if(!$appUser->checkResident($resident->id)) {
            $out['messages'][0]['code'] = "ResidentNotAllowed";
            $out['messages'][0]['message'] = "شهروند موردنظر به شما دسترسی ندارد";
            return $out;
        }
        $residetUserUsage = ResidentUserUsage::where('user_id', $appUser->id)->where('resident_id', $resident->id)->first();
        if(!$residetUserUsage) {
            $residetUserUsage = new ResidentUserUsage;
            $residetUserUsage->user_id = $appUser->id;
            $residetUserUsage->resident_id = $resident->id;
            $residetUserUsage->save();
        }
        $residetUserUsage->usage_count++;
        $residetUserUsage->save();

        $fieldId = (int)$request->input('field_id', 0);
        $fieldChangeValue = (float)$request->input('field_change_value', 0);

        $userProperty = UserProperty::where('user_id', $appUser->id)->where('redisdent_propery_fields_id', $fieldId)->first();
        if(!$userProperty) {
            $out['messages'][0]['code'] = "NoPermissionToField";
            $out['messages'][0]['message'] = "شما دسترسی به فیلد مورد نظر ندارید";
            return $out;
        }

        $field = ResidentPropertyField::find($fieldId);
        if(!$field) {
            $out['messages'][0]['code'] = "FieldNotFound";
            $out['messages'][0]['message'] = "فیلد مورد نظر پیدا نشد";
            return $out;
        }

        $fieldValue = ResidentPropertyValue::where('redient_property_fields_id', $fieldId)->where('resident_id', $resident->id)->first();
        if(!$fieldValue) {
            $fieldValue = new ResidentPropertyValue;
            $fieldValue->redient_property_fields_id = $fieldId;
            $fieldValue->resident_id = $resident->id;
            $fieldValue->value = $field->default_value;
            $fieldValue->save();
        }

        if($userProperty->max_change<abs($fieldChangeValue)) {
            $out['messages'][0]['code'] = "MaxChangeExceeded";
            $out['messages'][0]['message'] = "تغییرات ارسال شده بیشتر از حد مجاز است";
            return $out;
        }        

        if($userProperty->access_type=='inc' && $fieldChangeValue<0) {
            $out['messages'][0]['code'] = "DecreasePermissionDenied";
            $out['messages'][0]['message'] = "شما مجاز به کاهش مقدار نیستید";
            return $out;
        }

        if($userProperty->access_type=='dec' && $fieldChangeValue>0) {
            $out['messages'][0]['code'] = "IncreasePermissionDenied";
            $out['messages'][0]['message'] = "شما مجاز به افزایش مقدار نیستید";
            return $out;
        }

        if($userProperty->access_type!='viewonly') {
            $fieldValue->value += $fieldChangeValue;
            $fieldValue->save();
        }

        $out['messages'] = [];
        $out['status'] = 1;
        $out['data'] = [
            "value"=>$fieldValue->value
        ];

        Sequence::checkResidentUser($resident->id, $user->id);

        return $out;
    }

    public function updateResidentCoin(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $appUser = Users::where('email', $user->email)->first();
        $out = [
            "status"=>0,
            "messages"=>[
                [
                    "code"=>"UnknowError",
                    "message"=>"خطای نا مشخص",
                ],
            ],
        ];
        if(!$appUser) {
            $out['messages'][0]['code'] = "AppNotFound";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم ثبت نشده است";
            return $out;
        }
        if($appUser->status!='accepted') {
            $out['messages'][0]['code'] = "AppNotAccepted";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم تایید نشده است";
            return $out;
        }
        $mobile = $request->input('resident', '');
        $resident = Resident::where('mobile', $mobile)->first();
        if(!$resident) {
            $out['messages'][0]['code'] = "ResidentNotFound";
            $out['messages'][0]['message'] = "شهروندی با این شماره موبایل پیدا نشد";
            return $out;
        }
        if(!$appUser->checkResident($resident->id)) {
            $out['messages'][0]['code'] = "ResidentNotAllowed";
            $out['messages'][0]['message'] = "شهروند موردنظر به شما دسترسی ندارد";
            return $out;
        }
        $residetUserUsage = ResidentUserUsage::where('user_id', $appUser->id)->where('resident_id', $resident->id)->first();
        if(!$residetUserUsage) {
            $residetUserUsage = new ResidentUserUsage;
            $residetUserUsage->user_id = $appUser->id;
            $residetUserUsage->resident_id = $resident->id;
            $residetUserUsage->save();
        }
        $residetUserUsage->usage_count++;
        $residetUserUsage->save();

        $fieldId = -1;
        $fieldChangeValue = (float)$request->input('field_change_value', 0);

        $userProperty = UserProperty::where('user_id', $appUser->id)->where('redisdent_propery_fields_id', $fieldId)->first();
        if(!$userProperty) {
            $out['messages'][0]['code'] = "NoPermissionToField";
            $out['messages'][0]['message'] = "شما دسترسی به فیلد مورد نظر ندارید";
            return $out;
        }

        if($userProperty->max_change<abs($fieldChangeValue)) {
            $out['messages'][0]['code'] = "MaxChangeExceeded";
            $out['messages'][0]['message'] = "تغییرات ارسال شده بیشتر از حد مجاز است";
            return $out;
        }        

        if($userProperty->access_type=='inc' && $fieldChangeValue<0) {
            $out['messages'][0]['code'] = "DecreasePermissionDenied";
            $out['messages'][0]['message'] = "شما مجاز به کاهش مقدار نیستید";
            return $out;
        }

        if($userProperty->access_type=='dec' && $fieldChangeValue>0) {
            $out['messages'][0]['code'] = "IncreasePermissionDenied";
            $out['messages'][0]['message'] = "شما مجاز به افزایش مقدار نیستید";
            return $out;
        }

        if($userProperty->access_type!='viewonly') {
            $resident->coin += $fieldChangeValue;
            $resident->save();
        }

        $out['messages'] = [];
        $out['status'] = 1;
        $out['data'] = [
            "value"=>$resident->coin
        ];

        Sequence::checkResidentUser($resident->id, $user->id);

        return $out;
    }

    public function updateSelfField(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $appUser = Users::where('email', $user->email)->first();
        $out = [
            "status"=>0,
            "messages"=>[
                [
                    "code"=>"UnknowError",
                    "message"=>"خطای نا مشخص",
                ],
            ],
        ];
        if(!$appUser) {
            $out['messages'][0]['code'] = "AppNotFound";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم ثبت نشده است";
            return $out;
        }
        if($appUser->status!='accepted') {
            $out['messages'][0]['code'] = "AppNotAccepted";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم تایید نشده است";
            return $out;
        }
        $mobile = $request->input('resident', '');
        $fieldChangeValue = (float)$request->input('field_change_value', 0);
        $resident = Resident::where('mobile', $mobile)->first();
        if(!$resident) {
            $out['messages'][0]['code'] = "ResidentNotFound";
            $out['messages'][0]['message'] = "شهروندی با این شماره موبایل پیدا نشد";
            return $out;
        }
        if(!$appUser->checkResident($resident->id)) {
            $out['messages'][0]['code'] = "ResidentNotAllowed";
            $out['messages'][0]['message'] = "شهروند موردنظر به شما دسترسی ندارد";
            return $out;
        }
        $residetUserUsage = ResidentUserUsage::where('user_id', $appUser->id)->where('resident_id', $resident->id)->first();
        if(!$residetUserUsage) {
            $residetUserUsage = new ResidentUserUsage;
            $residetUserUsage->user_id = $appUser->id;
            $residetUserUsage->resident_id = $resident->id;
            $residetUserUsage->save();
        }
        $residetUserUsage->usage_count++;
        $residetUserUsage->save();

        $residentUserExp = ResidentUserExp::where('user_id', $appUser->id)->where('resident_id', $resident->id)->first();
        if(!$residentUserExp) {
            $residentUserExp = new ResidentUserExp;
            $residentUserExp->user_id = $appUser->id;
            $residentUserExp->resident_id = $resident->id;
            $residentUserExp->save();
        }
        $residentUserExp->exp_value += $fieldChangeValue;
        $residentUserExp->save();

        $out['messages'] = [];
        $out['status'] = 1;
        $out['data'] = [
            "value"=>$residentUserExp->exp_value,
        ];

        Sequence::checkResidentUser($resident->id, $user->id);
        
        return $out;
    }

    public function getSelfLeaderboard(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $appUser = Users::where('email', $user->email)->first();
        $out = [
            "status"=>0,
            "messages"=>[
                [
                    "code"=>"UnknowError",
                    "message"=>"خطای نا مشخص",
                ],
            ],
        ];
        if(!$appUser) {
            $out['messages'][0]['code'] = "AppNotFound";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم ثبت نشده است";
            return $out;
        }
        if($appUser->status!='accepted') {
            $out['messages'][0]['code'] = "AppNotAccepted";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم تایید نشده است";
            return $out;
        }
        $residentUserExps = ResidentUserExp::with('resident')->orderBy('exp_value', 'desc')->limit(100)->get();
        $leaderBorad = [];
        foreach($residentUserExps as $residentUserExp) {
            $leaderBorad[] = [
                "resident"=>$residentUserExp->resident->mobile,
                "value"=>$residentUserExp->exp_value,
            ];
        }

        $out['messages'] = [];
        $out['status'] = 1;
        $out['data'] = [
            "leaderboard"=>$leaderBorad,
        ];
        return $out;
    }

    public function fieldList(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $appUser = Users::where('email', $user->email)->first();
        $out = [
            "status"=>0,
            "messages"=>[
                [
                    "code"=>"UnknowError",
                    "message"=>"خطای نا مشخص",
                ],
            ],
        ];
        if(!$appUser) {
            $out['messages'][0]['code'] = "AppNotFound";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم ثبت نشده است";
            return $out;
        }
        if($appUser->status!='accepted') {
            $out['messages'][0]['code'] = "AppNotAccepted";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم تایید نشده است";
            return $out;
        }
        $userProperties = UserProperty::where('user_id', $appUser->id)->with('propertyfield')->get();
        $fields = [];
        foreach($userProperties as $userProperty) {
            $fields[] = [
                "field_id"=>$userProperty->redisdent_propery_fields_id,
                "field_name"=>$userProperty->propertyfield->field_name,
            ];
        }
        $out['status'] = 1;
        $out['messages'] = [];
        $out['data'] = [
            "fields"=>$fields,
        ];
        return $out;
    }

    public function residentProperties(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $appUser = Users::where('email', $user->email)->first();
        $out = [
            "status"=>0,
            "messages"=>[
                [
                    "code"=>"UnknowError",
                    "message"=>"خطای نا مشخص",
                ],
            ],
        ];
        if(!$appUser) {
            $out['messages'][0]['code'] = "AppNotFound";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم ثبت نشده است";
            return $out;
        }
        if($appUser->status!='accepted') {
            $out['messages'][0]['code'] = "AppNotAccepted";
            $out['messages'][0]['message'] = "اطلاعات شما در سیستم تایید نشده است";
            return $out;
        }
        $mobile = $request->input('resident', '');
        $resident = Resident::where('mobile', $mobile)->first();
        if(!$resident) {
            $out['messages'][0]['code'] = "ResidentNotFound";
            $out['messages'][0]['message'] = "شهروندی با این شماره موبایل پیدا نشد";
            return $out;
        }
        if(!$appUser->checkResident($resident->id)) {
            $out['messages'][0]['code'] = "ResidentNotAllowed";
            $out['messages'][0]['message'] = "شهروند موردنظر به شما دسترسی ندارد";
            return $out;
        }
        $residentProperties = [
            "experience"=>0,
        ];

        $residentUserExp = ResidentUserExp::where('user_id', $appUser->id)->where('resident_id', $resident->id)->first();
        if($residentUserExp) {
            $residentProperties['experience'] = $residentUserExp->exp_value;
        }
        $userProperties = UserProperty::where('user_id', $appUser->id)->get();
        $fields = [];
        foreach($userProperties as $userProperty) {
            $fields[] = $userProperty->redisdent_propery_fields_id;
        }

        $values = ResidentPropertyValue::whereIn('redient_property_fields_id', $fields)->get();
        foreach($values as $value) {
            $residentProperties['field_' . $value->redient_property_fields_id] = $value->value;
        }

        $out['messages'] = [];
        $out['status'] = 1;
        $out['data'] = [
            "properties"=>$residentProperties,
        ];
        return $out;
    }
}
