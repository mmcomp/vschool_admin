<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Course;

class CourseController extends Controller
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
        $courses = Course::all();
        
        return view('course.index', [
            "msgs"=>$msgs,
            "courses"=>$courses,
        ]);
    }

    public function create(Request $request) {
        $course = new Course;
        if(!$request->isMethod('post')) {
            return view('course.create', [
                "course"=>$course,
            ]);
        }

        $course->name = $request->input('name');
        $course->description = $request->input('description');
        $course->save();
        
        $request->session()->flash('msg_success', 'کرس مورد نظر با موفقیت ثبت شد');
        return redirect('/course');
    }

    public function edit(Request $request, $id) {
        $course = Course::find($id);
        if(!$course) {
            $request->session()->flash('msg_danger', 'کرس مورد نظر پیدا نشد');
            return redirect('/course');
        }

        if(!$request->isMethod('post')) {
            return view('course.create', [
                "course"=>$course,
            ]);
        }

        $course->name = $request->input('name');
        $course->description = $request->input('description');
        $course->save();
        
        $request->session()->flash('msg_success', 'کرس مورد نظر با موفقیت بروز شد');
        return redirect('/course');
    }


    public function delete(Request $request, $id) {
        $course = Course::find($id);
        if(!$course) {
            $request->session()->flash('msg_danger', 'کرس مورد نظر پیدا نشد');
            return redirect('/course');
        }

        $course->delete();
        
        $request->session()->flash('msg_success', 'کرس مورد نظر با موفقیت حذف شد');
        return redirect('/course');
    }

    public function indexSelect(Request $request) {
        $user = Auth::user();
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
        if($user->group_id!=0) {
            $user->load('questions');
            $ids = [];
            foreach($user->questions as $question_access) {
                $ids[] = $question_access->courses_id;
            }
            $courses = Course::whereIn('id', $ids)->get();
        }else {
            $courses = Course::all();
        }        
        return view('course_select.index', [
            "msgs"=>$msgs,
            "courses"=>$courses,
        ]);
    }
}
