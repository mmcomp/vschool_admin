<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Chapter;
use App\Course;
use App\Lesson;
use App\Page;
use App\Question;

class QuestionController extends Controller
{
    public function theIndex(Request $request, $id) {
        $course = Course::find($id);
        if(!$course) {
            $request->session()->flash('msg_danger', 'دوره مورد نظر پیدا نشد');
            return redirect('/course_select');
        }
        $user = Auth::user();
        $user->load('courses');
        if($user->group_id!=0) {
            $ok = false;
            foreach($user->courses as $theCourse) {
                if($theCourse->id==$id) {
                    $ok = true;
                }
            }
            if(!$ok) {
                $request->session()->flash('msg_danger', 'عدم وجود دسترسی به دوره مورد نظر');
                return redirect('/course_select');
            }
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

        
        $questions = Question::where('courses_id', $id)->get();

        return view('question.index', [
            "msgs"=>$msgs,
            "questions"=>$questions,
            "course"=>$course,
            "user"=>$user,
        ]);
    }

    public function create(Request $request, $id) {
        $course = Course::find($id);
        if(!$course) {
            $request->session()->flash('msg_danger', 'دوره مورد نظر پیدا نشد');
            return redirect('/course_select');
        }
        $user = Auth::user();  
        $question = new Question;      
        if(!$request->isMethod('post')) {
            return view('question.create', [
                "course"=>$course,
                "question"=>$question,
                "user"=>$user,
            ]);
        }

        $question->formulas = \json_decode($request->input('formulas'));
        $realChoices = [];
        if($request->input('question_type')!='answer') {
            $choices = $request->input('choises_answers');
            if($choices) {
                foreach($choices as $choice) {
                    $tmp = new \stdClass;
                    $tmp->checked = false;
                    $tmp->answer = $choice;
                    $realChoices[] = $tmp;
                }    
            }
            $checkedChoices = $request->input('choices');
            if($checkedChoices) {
                foreach($checkedChoices as $checkedChoice) {
                    $tmp = explode('_', $checkedChoice);
                    $tmp = (int)$tmp[1];
                    $realChoices[$tmp]->checked = true;
                }
            }
        }
        $question->question = $request->input('question');
        $question->question_type = $request->input('question_type');
        $question->answer = $request->input('answer');
        $question->choices = $realChoices;
        $question->score = $request->input('score');
        $question->courses_id = $id;
        if($user->group_id==0) {
            $question->literary_editor = ($request->input('literary_editor')!=null)?1:0;
            $question->scientific_editor = ($request->input('scientific_editor')!=null)?1:0;
            $question->layout_page_editor = ($request->input('layout_page_editor')!=null)?1:0;
        }
        $question->save();
        
        $request->session()->flash('msg_success', 'سوال مورد نظر با موفقیت ثبت شد');
        return redirect('/question/' . $id);
    }

    public function edit(Request $request, $id) {
        $user = Auth::user();  
        $question = Question::find($id); 
        if(!$question) {
            $request->session()->flash('msg_danger', 'سوال مورد نظر پیدا نشد');
            return redirect('/course_select');
        }
        $course = Course::find($question->courses_id);
        if(!$course) {
            $request->session()->flash('msg_danger', 'دوره مورد نظر پیدا نشد');
            return redirect('/course_select');
        }
        if(!$request->isMethod('post')) {
            return view('question.create', [
                "course"=>$course,
                "question"=>$question,
                "user"=>$user,
            ]);
        }

        $question->formulas = \json_decode($request->input('formulas'));
        $realChoices = [];
        if($request->input('question_type')!='answer') {
            $choices = $request->input('choises_answers');
            if($choices) {
                foreach($choices as $choice) {
                    $tmp = new \stdClass;
                    $tmp->checked = false;
                    $tmp->answer = $choice;
                    $realChoices[] = $tmp;
                }    
            }
            $checkedChoices = $request->input('choices');
            if($checkedChoices) {
                foreach($checkedChoices as $checkedChoice) {
                    $tmp = explode('_', $checkedChoice);
                    $tmp = (int)$tmp[1];
                    $realChoices[$tmp]->checked = true;
                }
            }
        }
        $question->question = $request->input('question');
        $question->question_type = $request->input('question_type');
        $question->answer = $request->input('answer');
        $question->choices = $realChoices;
        $question->score = $request->input('score');
        if($user->group_id==0) {
            $question->literary_editor = ($request->input('literary_editor')!=null)?1:0;
            $question->scientific_editor = ($request->input('scientific_editor')!=null)?1:0;
            $question->layout_page_editor = ($request->input('layout_page_editor')!=null)?1:0;
        }
        $question->save();
        
        $request->session()->flash('msg_success', 'سوال مورد نظر با موفقیت ویرایش شد');
        return redirect('/question/' . $course->id);

    }

    public function delete(Request $request, $id) {
        $user = Auth::user();  
        $question = Question::find($id); 
        if(!$question) {
            $request->session()->flash('msg_danger', 'سوال مورد نظر پیدا نشد');
            return redirect('/course_select');
        }
        $course = Course::find($question->courses_id);
        if(!$course) {
            $request->session()->flash('msg_danger', 'دوره مورد نظر پیدا نشد');
            return redirect('/course_select');
        }

        $question->delete();
        
        $request->session()->flash('msg_success', 'سوال مورد نظر با موفقیت حذف شد');
        return redirect('/question/' . $course->id);
    }
}
