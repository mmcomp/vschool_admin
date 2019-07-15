<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\CalendarUtils;
use Illuminate\Support\Facades\Hash;

use App\Users;
use App\Notification;
use App\User;
use App\UserProperty;
use App\UserCatagoryRestriction;
use App\ResidentPropertyField;
use App\Zone;
use App\UserPropertyTime;
use App\ResidentUserUsage;
use App\ResidentCatagory;

class RequestController extends Controller
{
    public function helpMain(Request $request) {
        return view('helps.index');
    }

    public function helpPage(Request $request, $page) {
        return view('helps.index');
    }

    public function editProfile(Request $request) {
        $password = $request->input('password', '');
        $mobile = $request->input('mobile', '');
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=2){
            if($mobile!='') {
                $loggedUser->mobile = $mobile;
            }
            if($password!='') {
                $loggedUser->password = $password;
            }
            if($mobile!='' || $password!='') {
                $loggedUser->save();
            }
        }
        return redirect('/');
    }

    public function index(Request $request) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id==1) {
            return redirect('/userlevels');
        }else if($loggedUser->group_id==2) {
            return redirect('/resident');
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
        // $users = Users::where('id', '>', 0)->orderBy('created_at', 'desc')->get();
        
        $theUsers = [];
        // foreach($users as $i=>$theUser) {
        //     $dateTmp = explode(' ', $theUser->created_at);
        //     $dateTmp = explode('-', $dateTmp[0]);
        //     $dateTmp = CalendarUtils::toJalali((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        //     $theUser->pcreated_at = $dateTmp[0] . '/' . $dateTmp[1] . '/' . $dateTmp[2];
        //     $count = ResidentUserUsage::where('user_id', $theUser->id)->count();
        //     if(!$count){
        //         $count = 0;
        //     }
        //     $theUser->usage_count = $count;
        //     $theUsers[] = $theUser;
        // }
        
        return view('home.admin', [
            "users"=>$theUsers,
            "msgs"=>$msgs,
        ]);
    }

    public function requestDelete(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $user = Users::find($id);
        if($user) {
            $user->delete();
            $request->session()->flash('msg_success', 'حذف درخواست با موفقیت انجام شد');
        }else {
            $request->session()->flash('msg_danger', 'درخواست مورد نظر پیدا نشد');
        }
        
        return redirect('/');
    }

    public function requestEdit(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }
        $user = Users::find($id);
        if(!$user) {
            $request->session()->flash('msg_danger', 'درخواست مورد نظر پیدا نشد');
            return redirect('/');
        }

        $dateTmp = explode(' ', $user->open_date);
        $dateTmp = explode('-', $dateTmp[0]);
        $dateTmp = CalendarUtils::toJalali((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        $user->open_date = $dateTmp[0] . '/' . $dateTmp[1] . '/' . $dateTmp[2];
        if(!$request->isMethod('post')) {
            return view('home.request', [
                "user"=>$user
            ]);
        }

        $user->name = $request->input('name');
        $user->encouragement_use_multiplier = $request->input('encouragement_use_multiplier');
        $user->experience_multiplier = $request->input('experience_multiplier');
        $user->company_name = $request->input('company_name');
        $user->national_id = $request->input('national_id');
        $user->register_id = $request->input('register_id');
        $user->mobile = $request->input('mobile');
        $user->tell = $request->input('tell');
        $user->address = $request->input('address');
        $user->website = $request->input('website');
        $user->ip = $request->input('ip');
        $user->email = $request->input('email');
        $user->status = $request->input('status');
        $user->class = $request->input('class');
        $dateTmp = explode('/', $request->input('open_date'));

        if((int)$dateTmp[0]>(int)$dateTmp[2]) {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[0], (int)$dateTmp[1], (int)$dateTmp[2]);
        }else {
            $dateTmp = CalendarUtils::toGregorian((int)$dateTmp[2], (int)$dateTmp[1], (int)$dateTmp[0]);
        }
        $user->open_date = $dateTmp[0] . '-' . $dateTmp[1] . '-' . $dateTmp[2] . ' 00:00:00';
        $user->save();

        if($user->status=='accepted') {
            $newUser = User::where('email', $user->email)->first();
            if(!$newUser) {
                $newUser = new User;
                $newUser->name = $user->name;
                $newUser->email = $user->email;
                $newUser->password = $user->password;
                $newUser->group_id = 1;
                $newUser->save();
                $res = Notification::SendSms($user->mobile, 'کاربری شما در سامانه بازی انگاری فعال گردید');
            }
        }
        
        $request->session()->flash('msg_success', 'درخواست مورد نظر با موفقیت بروز شد');
        return redirect('/');
    }

    public function permissions(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }

        $user = Users::find($id);
        if(!$user) {
            $request->session()->flash('msg_danger', 'درخواست مورد نظر پیدا نشد');
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

        $userProperties = UserProperty::where('user_id', $user->id)->with('propertyfield')->with('zone')->get();

        return view('user_permissions.index', [
            "user"=>$user,
            "userProperties"=>$userProperties,
            "msgs"=>$msgs,
        ]);
    }

    public function permissionsEdit(Request $request, $id, $user_id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }

        $userProperty = UserProperty::find($id);

        if(!$userProperty) {
            $request->session()->flash('msg_danger', 'دسترسی مورد نظر پیدا نشد');
            return redirect('/req/permissions/' . $user_id);
        }

        if($request->method()!='POST') {
            $userProperies = UserProperty::where('user_id', $user_id)->where('id', '!=', $userProperty->id)->get();
            $userPropertyIds = [];
            foreach($userProperies as $upr) {
                $userPropertyIds[] = $upr->redisdent_propery_fields_id;
            }
            $residentFields = ResidentPropertyField::whereNotIn('id', $userPropertyIds)->get();
            $coinField = new ResidentPropertyField;
            $coinField->id = -1;
            $coinField->field_name = 'سکه';
            $expField = new ResidentPropertyField;
            $expField->id = -2;
            $expField->field_name = 'تجربه';
            $residentFields[] = $coinField;
            $residentFields[] = $expField;
            $zones = Zone::all();
            return view('user_permissions.create', [
                "userProperty"=>$userProperty,
                "residentFields"=>$residentFields,
                "zones"=>$zones,
            ]);
        }

        $userProperty->redisdent_propery_fields_id = $request->input('redisdent_propery_fields_id');
        $userProperty->access_type = $request->input('access_type');
        $userProperty->max_change = (float)$request->input('max_change');
        $userProperty->weight = (float)$request->input('weight');
        $userProperty->zones_id = $request->input('zones_id');
        $userProperty->admin_id = $loggedUser->id;

        $userProperty->save();

        $request->session()->flash('msg_success', 'دسترسی مورد نظر ویرایش شد');
        return redirect('/req/permissions/' . $user_id);
    }

    public function permissionsCreate(Request $request, $user_id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }

        $userProperty = new UserProperty;

        if($request->method()!='POST') {
            $userProperies = UserProperty::where('user_id', $user_id)->get();
            $userPropertyIds = [];
            foreach($userProperies as $upr) {
                $userPropertyIds[] = $upr->redisdent_propery_fields_id;
            }
            $residentFields = ResidentPropertyField::whereNotIn('id', $userPropertyIds)->get();
            $coinField = new ResidentPropertyField;
            $coinField->id = -1;
            $coinField->field_name = 'سکه';
            $expField = new ResidentPropertyField;
            $expField->id = -2;
            $expField->field_name = 'تجربه';
            $residentFields[] = $coinField;
            $residentFields[] = $expField;
            $zones = Zone::all();
            return view('user_permissions.create', [
                "userProperty"=>$userProperty,
                "residentFields"=>$residentFields,
                "zones"=>$zones,
            ]);
        }

        $userProperty->user_id = $user_id;
        $userProperty->redisdent_propery_fields_id = $request->input('redisdent_propery_fields_id');
        $userProperty->access_type = $request->input('access_type');
        $userProperty->max_change = (float)$request->input('max_change');
        $userProperty->weight = (float)$request->input('weight');
        $userProperty->zones_id = $request->input('zones_id');
        $userProperty->admin_id = $loggedUser->id;

        $userProperty->save();

        $request->session()->flash('msg_success', 'دسترسی مورد نظر ثبت شد');
        return redirect('/req/permissions/' . $user_id);
    }

    public function permissionsDelete(Request $request, $id, $user_id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }

        $userProperty = UserProperty::find($id);

        if(!$userProperty) {
            $request->session()->flash('msg_danger', 'دسترسی مورد نظر پیدا نشد');
            return redirect('/req/permissions/' . $user_id);
        }

        $userProperty->delete();

        $request->session()->flash('msg_success', 'دسترسی مورد نظر حذف شد');
        return redirect('/req/permissions/' . $user_id);
    }

    public function permissionsTime(Request $request, $id, $user_id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }

        $userProperty = UserProperty::find($id);

        if(!$userProperty) {
            $request->session()->flash('msg_danger', 'دسترسی مورد نظر پیدا نشد');
            return redirect('/req/permissions/' . $user_id);
        }

        $residentPropertyField = ResidentPropertyField::find($userProperty->redisdent_propery_fields_id);
        if(!$residentPropertyField && $userProperty->redisdent_propery_fields_id<0) {
            $residentPropertyField = new ResidentPropertyField;
            $residentPropertyField->id = $userProperty->redisdent_propery_fields_id;
            $residentPropertyField->field_name = ($residentPropertyField->id==-1)?'سکه':'';
        }

        $user = Users::find($user_id);

        if(!$userProperty) {
            $request->session()->flash('msg_danger', 'درخواست مورد نظر پیدا نشد');
            return redirect('/req');
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

        $userPropertyTimes = UserPropertyTime::where('user_properties_id', $userProperty->id)->get();

        return view('user_permission_times.index', [
            "user"=>$user,
            "userProperty"=>$userProperty,
            "userPropertyTimes"=>$userPropertyTimes,
            "residentPropertyField"=>$residentPropertyField,
            "msgs"=>$msgs,
        ]);
    }

    public function permissionsTimeEdit(Request $request, $user_property_times_id,  $user_properties_id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }

        $userProperty = UserProperty::find($user_properties_id);

        if(!$userProperty) {
            $request->session()->flash('msg_danger', 'دسترسی مورد نظر پیدا نشد');
            return redirect('/req');
        }

        $userPropertyTime = UserPropertyTime::find($user_property_times_id);

        if(!$userPropertyTime) {
            $request->session()->flash('msg_danger', 'زمان دسترسی مورد نظر پیدا نشد');
            return redirect('/req/permissions_time/' . $user_properties_id . '/' . $userProperty->user_id);
        }

        if($request->method()!='POST') {
            return view('user_permission_times.create', [
                "userPropertyTime"=>$userPropertyTime,
            ]);
        }

        $userPropertyTime->start_time = $request->input('start_time');
        $userPropertyTime->end_time = $request->input('end_time');


        $userPropertyTime->save();

        $request->session()->flash('msg_success', 'زمان دسترسی مورد نظر ویرایش شد');
        return redirect('/req/permissions_time/' . $user_properties_id . '/' . $userProperty->user_id);
    }

    public function permissionsTimeCreate(Request $request, $user_properties_id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }

        $userProperty = UserProperty::find($user_properties_id);

        if(!$userProperty) {
            $request->session()->flash('msg_danger', 'دسترسی مورد نظر پیدا نشد');
            return redirect('/req');
        }

        $userPropertyTime = new UserPropertyTime;

        if($request->method()!='POST') {
            return view('user_permission_times.create', [
                "userPropertyTime"=>$userPropertyTime,
            ]);
        }

        $userPropertyTime->user_properties_id = $userProperty->id;
        $userPropertyTime->start_time = $request->input('start_time');
        $userPropertyTime->end_time = $request->input('end_time');


        $userPropertyTime->save();

        $request->session()->flash('msg_success', 'زمان دسترسی مورد نظر ایجاد شد');
        return redirect('/req/permissions_time/' . $user_properties_id . '/' . $userProperty->user_id);
    }

    public function permissionsTimeDelete(Request $request, $user_property_times_id, $user_properties_id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }

        $userProperty = UserProperty::find($user_properties_id);

        if(!$userProperty) {
            $request->session()->flash('msg_danger', 'دسترسی مورد نظر پیدا نشد');
            return redirect('/req');
        }

        $userPropertyTime = UserPropertyTime::find($user_property_times_id);

        if(!$userPropertyTime) {
            $request->session()->flash('msg_danger', 'زمان دسترسی مورد نظر پیدا نشد');
            return redirect('/req/permissions_time/' . $user_properties_id . '/' . $userProperty->user_id);
        }

        $userPropertyTime->delete();

        $request->session()->flash('msg_success', 'زمان دسترسی مورد نظر حذف شد');
        return redirect('/req/permissions_time/' . $user_properties_id . '/' . $userProperty->user_id);

    }

    public function catagory(Request $request, $id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }

        $user = Users::find($id);
        if(!$user) {
            $request->session()->flash('msg_danger', 'درخواست مورد نظر پیدا نشد');
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

        $userProperties = UserCatagoryRestriction::where('user_id', $user->id)->with('catagory')->get();

        return view('user_catagory.index', [
            "user"=>$user,
            "userProperties"=>$userProperties,
            "msgs"=>$msgs,
        ]);
    }

    public function catagoryEdit(Request $request, $id, $user_id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }

        $userProperty = UserCatagoryRestriction::find($id);

        if(!$userProperty) {
            $request->session()->flash('msg_danger', 'دسته مورد نظر پیدا نشد');
            return redirect('/req/catagory/' . $user_id);
        }

        if($request->method()!='POST') {
            $residentCatagories = ResidentCatagory::all();
            return view('user_catagory.create', [
                "userProperty"=>$userProperty,
                "residentCatagories"=>$residentCatagories,
            ]);
        }

        $userProperty->resident_catagories_id = $request->input('resident_catagories_id');
        $userProperty->min_exp = (int)$request->input('min_exp');

        $userProperty->save();

        $request->session()->flash('msg_success', 'دسته مورد نظر ویرایش شد');
        return redirect('/req/catagory/' . $user_id);
    }

    public function catagoryCreate(Request $request, $user_id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }

        $userProperty = new UserCatagoryRestriction;

        if($request->method()!='POST') {
            $residentCatagories = ResidentCatagory::all();
            return view('user_catagory.create', [
                "userProperty"=>$userProperty,
                "residentCatagories"=>$residentCatagories,
            ]);
        }

        $userProperty->user_id = $user_id;
        $userProperty->resident_catagories_id = $request->input('resident_catagories_id');
        $userProperty->min_exp = (int)$request->input('min_exp');

        $userProperty->save();

        $request->session()->flash('msg_success', 'دسته مورد نظر ثبت شد');
        return redirect('/req/catagory/' . $user_id);
    }

    public function catagoryDelete(Request $request, $id, $user_id) {
        $loggedUser = Auth::user();
        if($loggedUser->group_id!=0) {
            return redirect('/');
        }

        $userProperty = UserCatagoryRestriction::find($id);

        if(!$userProperty) {
            $request->session()->flash('msg_danger', 'دسته مورد نظر پیدا نشد');
            return redirect('/req/catagory/' . $user_id);
        }

        $userProperty->delete();

        $request->session()->flash('msg_success', 'دسته مورد نظر حذف شد');
        return redirect('/req/catagory/' . $user_id);
    }
}
