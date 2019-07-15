<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\UserLevel;
use App\Users;

class UserTokenController extends Controller
{
    public function index(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=1) {
            return redirect('/');
        }
        $loggedUser = Auth::user();
        $user = Users::where('email', $loggedUser->email)->first();
        $token = '';
        if($user) {
            if($user->token!=null && $user->token!='') {
                $token = $user->token;
            }else
            {
                $token = $user->getToken();
                $user->token = $token;
                $user->save();
            }
        }
        
        return view('token.index', [
            "token"=>$token
        ]);
    }
}
