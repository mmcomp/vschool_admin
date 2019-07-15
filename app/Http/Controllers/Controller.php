<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\CalendarUtils;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

use JWTAuth;

use App\Users;
// use App\Notification;
// use App\Verification;
// use App\User;
// use App\Resident;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function login(Request $request) {
        $msg = '';
        $msg_type = '';
        if($request->method()=='POST') {
            $credentials = $request->only('email', 'password');
            if(Auth::attempt($credentials)) {
                // return 'LOGED';
                return redirect('/');
            }else {
                $msg = 'نام کاربری یا رمز عبور اشتباه است';
                $msg_type =  'danger';
            }
        }else{
            Auth::logout();
        }
        return view('layouts.login', [
            'msg' => $msg,
            'msg_type' => $msg_type
        ]);
    }
    /*
    public function rLogin(Request $request) {
        $msg = '';
        $msg_type = '';
        if($request->method()=='POST') {
            $credentials = $request->only('email', 'password');
            // var_dump($credentials);
            if(Auth::attempt($credentials)) {
                // return 'LOGED';
                return redirect('/');
            }else {
                // return 'NOTLOGED';
                $msg = 'موبایل یا کد تایید اشتباه است';
                $msg_type =  'danger';
            }
        }else{
            Auth::logout();
        }
        return view('layouts.residentlogin', [
            'msg' => $msg,
            'msg_type' => $msg_type
        ]);
    }

    public function rVerifyMobile(Request $request, $mobile) {
        $verifyCode = $this->generateRandomString();
        $verify = Verification::where('mobile', $mobile)->first();
        $out = ["status"=>1];
        if(!$verify) {
            $verify = new Verification;
            $verify->verify_code = $verifyCode;
            $verify->mobile = $mobile;
            $verify->save();    
        }else {
            if($verify->trycount<6) {
                $verify->trycount++;
                $verify->verify_code = $verifyCode;
                $verify->save();
            }else if(strtotime(date("Y-m-d H:i:s")) - strtotime($verify->updated_at)>=30*60*60) {
                $verify->trycount=1;
                $verify->verify_code = $verifyCode;
                $verify->save();
            }else {
                $out = ["status"=>0];
                return $out;
            }
        }
        $res = Notification::SendSms($mobile, 'کد صحت سنجی شما :' . $verifyCode);
        $user = User::where('email', $mobile)->first();
        if(!$user) {
            $user = new User;
            $user->email = $mobile;            
            $user->group_id = 2;
            $resident = Resident::where('mobile', $mobile)->first();
            if(!$resident) {
                $resident = new Resident;
                $resident->mobile = $mobile;
                $resident->first_name = '';
                $resident->last_name = $mobile;
                $resident->save();
            }
            $user->name = $resident->first_name . (($resident->first_name && $resident->first_name!='')?' ':'') . $resident->last_name;
        }            
        $user->password = $verifyCode;
        $user->save();
        return $out;
    }
    
    public function apilogin(Request $request){
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    "status"=>0,
                    "messages"=>[
                        [
                            "code"=>"InvalidCredentials",
                            "message"=>"اطلاعات ورود غلط هستند"
                        ]
                    ],
                    "data"=>[]
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                "status"=>0,
                "messages"=>[
                    [
                        "code"=>"ErrorInTokenCreation",
                        "message"=>"خطا در تولید توکن"
                    ]
                ],
                "data"=>[]
            ], 500);
        }

        return response()->json([
            "status"=>1,
            "messages"=>[],
            "data"=>[
                "token"=>$token
            ]
        ]);
    }

    public function getAuthenticatedUser(){
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], [
                "status"=>0,
                "messages"=>[
                    [
                        "code"=>"TokenExpired",
                        "message"=>"توکن شما منقضی شده است"
                    ]
                ],
                "data"=>[]
            ]);//$e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }

    public function register(Request $request) {
        $msg = '';
        $msg_type = '';
        if($request->method()=='POST') {
            $user = new Users;
            $user->name = $request->input('name');
            $user->company_name = $request->input('company_name');
            $user->national_id = $request->input('national_id');
            $tmp = explode('/', $request->input('open_date'));
            if($tmp[0]>$tmp[2]) {
                $tmp = \Morilog\Jalali\CalendarUtils::toGregorian($tmp[0], $tmp[1], $tmp[2]);
            }else {
                $tmp = \Morilog\Jalali\CalendarUtils::toGregorian($tmp[2], $tmp[1], $tmp[0]);
            }
            $user->open_date = $tmp[0] . '-' . $tmp[1] . '-' .$tmp[2];
            $user->register_id = $request->input('register_id');
            $user->mobile = $request->input('mobile');
            $user->tell = $request->input('tell');
            $user->address = $request->input('address');
            $user->website = $request->input('website');
            $user->password = $request->input('password');
            $user->ip = $request->input('ip');
            $user->email = $request->input('email');

            if($request->hasFile('image_path') && $request->file('image_path')->isValid()) {
                $image_path = 'icon_' . strtotime(date("Y-m-d H:i:s")) . '.' . $request->image_path->getClientOriginalExtension();
                $request->image_path->move('app_icons/' ,  $image_path);
                $user->image_path = '/app_icons/' . $image_path;
            }

            $user->save();

            $msg = 'ثبت نام اولیه شما با موفقیت انجام شد';
            $msg .= '<br/>';
            $msg .= 'کد ثبت نام شما ' . $user->id . ' می باشد';
            $msg_type =  'success';
            $res = Notification::SendSms($user->mobile, $msg);
        }
        return view('layouts.register', [
            'msg' => $msg,
            'msg_type' => $msg_type
        ]);
    }

    public function generateRandomString($length = 4) {
        $characters = '0123456789';//'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function verifyMobile(Request $request, $mobile) {
        $verifyCode = $this->generateRandomString();
        $verify = Verification::where('mobile', $mobile)->first();
        $out = ["status"=>1];
        if(!$verify) {
            $verify = new Verification;
            $verify->verify_code = $verifyCode;
            $verify->mobile = $mobile;
            $verify->save();    
        }else {
            if($verify->trycount<6) {
                $verify->trycount++;
                $verify->verify_code = $verifyCode;
                $verify->save();
            }else if(strtotime(date("Y-m-d H:i:s")) - strtotime($verify->updated_at)>=30*60*60) {
                $verify->trycount=1;
                $verify->verify_code = $verifyCode;
                $verify->save();
            }else {
                $out = ["status"=>0];
                return $out;
            }
        }
        $res = Notification::SendSms($mobile, 'کد صحت سنجی شما :' . $verifyCode);

        return $out;
    }

    public function resetPassword(Request $request) {
        $email = $request->input('email', '');
        $user = User::where('email', $email)->first();
        $out = ["status"=>0];
        if($user && isset($user->mobile) && $user->mobile!='') {
            $out['status'] = 1;
            $verifyCode = $this->generateRandomString();
            $out['password'] = $verifyCode;
            $user->password = $verifyCode;
            $user->save();
            $res = Notification::SendSms($user->mobile, 'رمز عبور جدید شما : ' . "\n" . $verifyCode);
        }

        return $out;
    }

    public function verifyCode(Request $request, $mobile, $verify_code) {
        $verify = Verification::where('mobile', $mobile)->first();
        $out = ["status"=>0];
        if($verify->verify_code == $verify_code) {
            $out["status"] = 1;
            $verify->delete();
        }
        return $out;
    }

    public function under(Request $request) {
        $request->session()->flash('msg_success', 'در دست بروز رسانی');
        
        return redirect('/');
    }
    */
}
