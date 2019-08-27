<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Chapter;
use App\Course;

class ChapterController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();
        $user->load('courses');
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
        $changeCourse = ($request->input('courses_id')!=null);
        $courses_id = '';
        if($changeCourse) {
            $courses_id = $request->input('courses_id');
            $chapters = Chapter::where('courses_id', $request->input('courses_id'))->with('course')->get();
            if($user->group_id==0) {
                $allCourses = Course::all();
            }else {
                $courses = [];
                foreach($user->courses as $course) {
                    $courses[] = $course->id;
                }
                $allCourses = Course::whereIn('id', $courses)->get();
            }
        }else {
            if($user->group_id==0) {
                $chapters = Chapter::where('id', '!=', 0)->with('course')->get();
                $allCourses = Course::all();
            }else {
                $courses = [];
                foreach($user->courses as $course) {
                    $courses[] = $course->id;
                }
                $chapters = Chapter::whereIn('courses_id', $courses)->with('course')->get();
                $allCourses = Course::whereIn('id', $courses)->get();
            }
        }

        return view('chapter.index', [
            "msgs"=>$msgs,
            "chapters"=>$chapters,
            "allCourses"=>$allCourses,
            "courses_id"=>$courses_id,
        ]);
    }

    public function create(Request $request) {
        $user = Auth::user();
        $user->load('courses');
        if($user->group_id==0) {
            $courses = Course::all();
        }else {
            $courseids = [];
            foreach($user->courses as $course) {
                $courseids[] = $course->id;
            }
            $courses = Course::whereIn('id', $courseids)->get();
        }
        $chapter = new Chapter;
        
        if(!$request->isMethod('post')) {
            return view('chapter.create', [
                "chapter"=>$chapter,
                "courses"=>$courses
            ]);
        }

        $chapter->name = $request->input('name');
        $chapter->description = $request->input('description');
        $chapter->courses_id = $request->input('courses_id');
        $chapter->save();
        
        $request->session()->flash('msg_success', 'چپتر مورد نظر با موفقیت ثبت شد');
        return redirect('/chapter');
    }

    public function edit(Request $request, $id) {
        $user = Auth::user();
        $user->load('courses');
        $chapter = Chapter::find($id);
        if(!$chapter) {
            $request->session()->flash('msg_danger', 'چپتر مورد نظر پیدا نشد');
            return redirect('/chapter');
        }

        if($user->group_id==0) {
            $courses = Course::all();
        }else {
            $courseids = [];
            foreach($user->courses as $course) {
                $courseids[] = $course->id;
            }
            $courses = Course::whereIn('id', $courseids)->get();
        }
        if(!$request->isMethod('post')) {
            return view('chapter.create', [
                "chapter"=>$chapter,
                "courses"=>$courses
            ]);
        }

        $chapter->name = $request->input('name');
        $chapter->description = $request->input('description');
        $chapter->courses_id = $request->input('courses_id');
        $chapter->save();
        
        $request->session()->flash('msg_success', 'چپتر مورد نظر با موفقیت بروز شد');
        return redirect('/chapter');
    }


    public function delete(Request $request, $id) {
        $chapter = Chapter::find($id);
        if(!$chapter) {
            $request->session()->flash('msg_danger', 'چپتر مورد نظر پیدا نشد');
            return redirect('/chapter');
        }

        $chapter->delete();
        
        $request->session()->flash('msg_success', 'چپتر مورد نظر با موفقیت حذف شد');
        return redirect('/chapter');
    }
}
