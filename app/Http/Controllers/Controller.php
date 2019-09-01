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
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
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

        $user = new User;
        $tmpOnlineUsers = $user->allOnline();
        $onlineUsers = [];
        foreach($tmpOnlineUsers as $onlineUser) {
            if(!$onlineUser->logout) {
                $onlineUsers[] = $onlineUser;
            }
        }

        return view('home.dashboard', [
            "msgs"=>$msgs,
            "onlineUsers"=>$onlineUsers,
        ]);
    }

    public function forceLogout(Request $request, $id) {
        $user = User::find($id);
        if($user) {
            $user->logout = true;
            $user->save();
        }

        return redirect('/');
    }

    public function changePass(Request $request) {
        $user = Auth::getUser();
        $credentials = ['email'=>$user->email, 'password'=>$request->input('password')];
        if(Auth::attempt($credentials)) {
            if($request->input('newpassword')!=$request->input('newpassword2')) {
                $request->session()->flash('msg_danger', 'رمز جدید با تکرار آن برابر نیست');
            }else if($request->input('newpassword')=='') {
                $request->session()->flash('msg_danger', 'رمز جدید خالی می باشد');
            }else {
                $user->password = $request->input('newpassword');
                $user->save();
                $request->session()->flash('msg_success', 'رمز عبور با موفقیت تغییر یافت');
            }
        }else {
            $request->session()->flash('msg_danger', 'رمز حال حاضر اشتباه است');
        }

        return redirect('/');
    }

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

    public function test(Request $request) {
        $baseUrl = 'http://latex2png.com';
        $client = new \GuzzleHttp\Client();
        $body = \json_encode([
            'auth'=>[
                'user'=>'guest',
                'password'=>'guest'
            ],
            'latex'=>'x^2',
            'resolution'=>600,
            'color'=>'000000'
        ]);
        $response = $client->request('POST', $baseUrl . '/api/convert', [
            'body' => $body,
            'headers' => ['Content-Type'=>'application/json']
        ]);
        $out = [
            'status'=>0,
            'message'=>'Undefined Error',
            'image_path'=>''
        ];
        if($response->getStatusCode()==200) {
            $result = \json_decode($response->getBody());
            if($result->{'result-code'}==0) {
                $imageUrl = $baseUrl . $result->url;
                $out['image_path'] = $imageUrl;
                $out['message'] = '';
                $out['status'] = 1;
                $image = \file_get_contents($imageUrl);
                $fileName = public_path() . '/admin/dist/img/tex/1.png';
                \file_put_contents($fileName, $image);
            }else {
                $out['message'] = $result->{'result-message'};
            }
        }else {
            $out['message'] = $response->getReasonPhrase();
        }
        return $out;
    }
}
