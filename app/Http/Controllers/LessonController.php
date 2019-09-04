<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Chapter;
use App\Course;
use App\Lesson;
use App\Page;
use App\Question;

class LessonController extends Controller
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
        $changeChapter = ($request->input('chapters_id')!=null);
        $chapters_id = '';
        if($changeChapter) {
            $chapters_id = $request->input('chapters_id');
            $lessons = Lesson::where('chapters_id', $chapters_id)->with(['chapter', 'chapter.course', 'pages'])->get();
            if($user->group_id==0) {
                $allChapters = Chapter::all();
            }else {
                $courses = [];
                foreach($user->courses as $course) {
                    $courses[] = $course->id;
                }
                $allChapters = Chapter::whereIn('courses_id', $courses)->get();
            }
        }else {
            if($user->group_id==0) {
                $lessons = Lesson::where('id', '!=', 0)->with(['chapter', 'chapter.course'])->get();
                $allChapters = Chapter::all();
            }else {
                $courses = [];
                foreach($user->courses as $course) {
                    $courses[] = $course->id;
                }
                $allChapters = Chapter::whereIn('courses_id', $courses)->get();
                $chapters = [];
                foreach($allChapters as $chapter) {
                    $chapters[] = $chapter->id;
                }
                $lessons = Lesson::whereIn('chapters_id', $chapters)->with(['chapter', 'chapter.course'])->get();
            }
        }

        return view('lesson.index', [
            "msgs"=>$msgs,
            "lessons"=>$lessons,
            "allChapters"=>$allChapters,
            "chapters_id"=>$chapters_id,
        ]);
    }

    public function create(Request $request) {
        $user = Auth::user();
        $user->load('courses');
        if($user->group_id==0) {
            $chapters = Chapter::all();
        }else {
            $courseids = [];
            foreach($user->courses as $course) {
                $courseids[] = $course->id;
            }
            $chapters = Chapter::whereIn('courses_id', $courseids)->get();
        }
        $lesson = new Lesson;
        
        if(!$request->isMethod('post')) {
            return view('lesson.create', [
                "lesson"=>$lesson,
                "chapters"=>$chapters
            ]);
        }

        $lesson->name = $request->input('name');
        $lesson->description = $request->input('description');
        $lesson->chapters_id = $request->input('chapters_id');
        $lesson->save();
        
        $request->session()->flash('msg_success', 'درس مورد نظر با موفقیت ثبت شد');
        return redirect('/lesson');
    }

    public function edit(Request $request, $id) {
        $lesson = Lesson::find($id);
        if(!$lesson) {
            $request->session()->flash('msg_danger', 'درس مورد نظر پیدا نشد');
            return redirect('/lesson');
        }
        $user = Auth::user();
        $user->load('courses');
        if($user->group_id==0) {
            $chapters = Chapter::all();
        }else {
            $courseids = [];
            foreach($user->courses as $course) {
                $courseids[] = $course->id;
            }
            $chapters = Chapter::whereIn('courses_id', $courseids)->get();
        }
        
        if(!$request->isMethod('post')) {
            return view('lesson.create', [
                "lesson"=>$lesson,
                "chapters"=>$chapters
            ]);
        }

        $lesson->name = $request->input('name');
        $lesson->description = $request->input('description');
        $lesson->chapters_id = $request->input('chapters_id');
        $lesson->save();
        
        $request->session()->flash('msg_success', 'درس مورد نظر با موفقیت بروزسانی شد');
        return redirect('/lesson');

    }

    public function delete(Request $request, $id) {
        $lesson = Lesson::find($id);
        if(!$lesson) {
            $request->session()->flash('msg_danger', 'درس مورد نظر پیدا نشد');
            return redirect('/lesson');
        }

        $lesson->delete();
        
        $request->session()->flash('msg_success', 'درس مورد نظر با موفقیت حذف شد');
        return redirect('/lesson');
    }

    public function page(Request $request, $id) {
        $lesson = Lesson::find($id);
        if(!$lesson) {
            $request->session()->flash('msg_danger', 'درس مورد نظر پیدا نشد');
            return redirect('/lesson');
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

        $lesson->load(['pages', 'chapter', 'chapter.course']);

        return view('lesson.page', [
            "msgs"=>$msgs,
            "lesson"=>$lesson,
        ]);
    }

    public function pageCreate(Request $request, $id) {
        $lesson = Lesson::find($id);
        $page = new Page;
        if(!$lesson) {
            $request->session()->flash('msg_danger', 'درس مورد نظر پیدا نشد');
            return redirect('/lesson');
        }
        
        if(!$request->isMethod('post')) {
            return view('lesson.page_create', [
                "lesson"=>$lesson,
                "page"=>$page
            ]);
        }

        $page->lessons_id = $id;
        $thepage = json_decode($request->input('page'));
        if($request->image) {
            $thepage->image = $request->image->store('page_images');
        }
        $page->page = $thepage;
        $page->save();

        $question = new Question;
        $realChoices = [];
        if($request->input('question_type')!='answer') {
            $choices = $request->input('choises_answers');
            foreach($choices as $choice) {
                $tmp = new \stdClass;
                $tmp->checked = false;
                $tmp->answer = $choice;
                $realChoices[] = $tmp;
            }
            $checkedChoices = $request->input('choices');
            foreach($checkedChoices as $checkedChoice) {
                $tmp = explode('_', $checkedChoice);
                $tmp = (int)$tmp[1];
                $realChoices[$tmp]->checked = true;
            }
        }
        $page->load('lesson.chapter');
        $question->question = $request->input('question');
        $question->question_type = $request->input('question_type');
        $question->answer = $request->input('answer');
        $question->choices = $realChoices;
        $question->score = $request->input('score');
        $question->pages_id = $page->id;
        $question->lessons_id = $page->lessons_id;
        $question->courses_id = $page->lesson->chapter->courses_id;
        $question->save();


        $request->session()->flash('msg_success', 'صفحه مورد نظر با موفقیت ثبت شد');
        return redirect('/lesson/page/' . $id);
    }

    public function pageEdit(Request $request, $id) {
        $page = Page::find($id);
        if(!$page) {
            $request->session()->flash('msg_danger', 'صفحه مورد نظر پیدا نشد');
            return redirect('/lesson');
        }

        $page->load(['lesson', 'question']);
        if(!$request->isMethod('post')) {
            return view('lesson.page_create', [
                "page"=>$page
            ]);
        }

        $thepage = json_decode($request->input('page'));
        if($request->image) {
            $thepage->image = $request->image->store('page_images');
        }
        $page->page = $thepage;
        $page->save();

        $realChoices = [];
        if($request->input('question_type')!='answer') {
            $choices = $request->input('choises_answers');
            foreach($choices as $choice) {
                $tmp = new \stdClass;
                $tmp->checked = false;
                $tmp->answer = $choice;
                $realChoices[] = $tmp;
            }
            $checkedChoices = $request->input('choices');
            foreach($checkedChoices as $checkedChoice) {
                $tmp = explode('_', $checkedChoice);
                $tmp = (int)$tmp[1];
                $realChoices[$tmp]->checked = true;
            }
        }

        if($page->question) {
            $page->question()->update([
                "question"=>$request->input('question'),
                "question_type"=>$request->input('question_type'),
                "answer"=>$request->input('answer'),
                "choices"=>$realChoices,
                "score"=>$request->input('score'),
                "courses_id"=>$page->lesson->chapter->courses_id
            ]);
        }else {
            $question = new Question;
            $question->question = $request->input('question');
            $question->question_type = $request->input('question_type');
            $question->answer = $request->input('answer');
            $question->choices = $realChoices;
            $question->score = $request->input('score');
            $question->pages_id = $page->id;
            $question->lessons_id = $page->lessons_id;
            $question->courses_id = $page->lesson->chapter->courses_id;
            $question->save();
        }


        $request->session()->flash('msg_success', 'صفحه مورد نظر با موفقیت بروز شد');
        return redirect('/lesson/page/' . $page->lessons_id);
    }

    public function pageDelete(Request $request, $id) {
        $page = Page::find($id);
        if(!$page) {
            $request->session()->flash('msg_danger', 'صفحه مورد نظر پیدا نشد');
            return redirect('/lesson');
        }

        $page->delete();
        
        $request->session()->flash('msg_success', 'صفحه مورد نظر با موفقیت حذف شد');
        return redirect('/lesson/page/' . $page->lessons_id);
    }
}
