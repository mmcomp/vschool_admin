<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Course;

class UserController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();
        if($user->group_id!=0) {
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
        $teachers = User::where('group_id', 2)->get();
        
        return view('user.index', [
            "msgs"=>$msgs,
            "teachers"=>$teachers,
        ]);
    }

    public function create(Request $request) {
        $user = new User;
        if(!$request->isMethod('post')) {
            return view('user.create', [
                "teacher"=>$user,
            ]);
        }

        $email = trim($request->input('email'));
        if($email=='') {
            $request->session()->flash('msg_danger', 'ایمیل نمی بایست خالی باشد');
            return redirect('/user');
        }

        $otheruser = User::where('email', $email)->first();
        if($otheruser) {
            $request->session()->flash('msg_danger', 'ایمیل تکراری است');
            return redirect('/user');
        }
        $user->fname = $request->input('fname');
        $user->lname = $request->input('lname');
        $user->email = $email;
        $user->password = $request->input('password');
        $user->group_id = 2;
        $user->save();
        
        $request->session()->flash('msg_success', 'متخصص مورد نظر با موفقیت ثبت شد');
        return redirect('/user');
    }

    public function edit(Request $request, $id) {
        $user = User::find($id);
        if(!$user) {
            $request->session()->flash('msg_danger', 'متخصص مورد نظر پیدا نشد');
            return redirect('/user');
        }

        if(!$request->isMethod('post')) {
            return view('user.create', [
                "teacher"=>$user,
            ]);
        }

        $email = trim($request->input('email'));
        if($email=='') {
            $request->session()->flash('msg_danger', 'ایمیل نمی بایست خالی باشد');
            return redirect('/user');
        }
        
        $otheruser = User::where('email', $email)->first();
        if($otheruser) {
            $request->session()->flash('msg_danger', 'ایمیل تکراری است');
            return redirect('/user');
        }
        $user->fname = $request->input('fname');
        $user->lname = $request->input('lname');
        $user->email = $email;
        $user->password = $request->input('password');
        $user->save();
        
        $request->session()->flash('msg_success', 'متخصص مورد نظر با موفقیت بروز شد');
        return redirect('/user');
    }


    public function delete(Request $request, $id) {
        $user = User::find($id);
        if(!$user) {
            $request->session()->flash('msg_danger', 'متخصص مورد نظر پیدا نشد');
            return redirect('/user');
        }

        $user->delete();
        
        $request->session()->flash('msg_success', 'متخصص مورد نظر با موفقیت حذف شد');
        return redirect('/user');
    }

    public function course(Request $request, $id) {
        $user = Auth::user();
        if($user->group_id!=0) {
            return redirect('/');
        }
        $user = User::find($id);
        if(!$user) {
            $request->session()->flash('msg_danger', 'متخصص مورد نظر پیدا نشد');
            return redirect('/user');
        }
        $user->load(['courses']);
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
        
        return view('user.course', [
            "msgs"=>$msgs,
            "teacher"=>$user,
        ]);
    }

    public function courseCreate(Request $request, $id) {
        $user = User::find($id);
        if(!$user) {
            $request->session()->flash('msg_danger', 'متخصص مورد نظر پیدا نشد');
            return redirect('/user');
        }
        $user->load(['courses']);
        $courseIds = [];
        foreach($user->courses as $course) {
            $courseIds[] = $course->id;
        }
        $courses = Course::whereNotIn('id', $courseIds)->get();
        if(count($courses)==0) {
            $request->session()->flash('msg_danger', 'دوره ای برای دسترسی پیدا نشد');
            return redirect('/user/course/' . $id);
        }
        if(!$request->isMethod('post')) {
            return view('user.course_create', [
                "courses"=>$courses
            ]);
        }
        $course = Course::find($request->input('courses_id'));
        if(!$course) {
            $request->session()->flash('msg_danger', 'دوره مورد نظر پیدا نشد');
            return redirect('/user/course/' . $id);
        }
        $course->teacher_id = $user->id;
        $course->save();

        $request->session()->flash('msg_success', 'دسترسی مورد نظر با موفقیت ثبت شد');
        return redirect('/user/course/' . $id);
    }

    public function courseDelete(Request $request, $id, $course_id) {
        $course = Course::find($course_id);
        if(!$course) {
            $request->session()->flash('msg_danger', 'دوره مورد نظر پیدا نشد');
            return redirect('/user/course/' . $id);
        }

        $course->teacher_id = 0;
        $course->save();
        
        $request->session()->flash('msg_success', 'دسترسی به دوره مورد نظر با موفقیت حذف شد');
        return redirect('/user/course/' . $id);
    }
}
