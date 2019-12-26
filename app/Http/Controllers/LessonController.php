<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        if($request->input('sw1')) {
            $order1 = (int)$request->input('sw1');
            $order2 = (int)$request->input('sw2');
            $chapters_id = $request->input('chapters_id');
            $lesson1 = Lesson::where('chapters_id', $chapters_id)->where('lesson_order', $order1-1)->first();
            $lesson2 = Lesson::where('chapters_id', $chapters_id)->where('lesson_order', $order2-1)->first();
            if(!$lesson1 || !$lesson2) {
                return ["status"=>0];
            }

            $lesson1->lesson_order = $order2-1;
            $lesson1->save();

            $lesson2->lesson_order = $order1-1;
            $lesson2->save();

            return ["status"=>1];
        }
        $changeChapter = ($request->input('chapters_id')!=null);
        $chapters_id = '';
        if($changeChapter) {
            $chapters_id = $request->input('chapters_id');
            $lessons = Lesson::where('chapters_id', $chapters_id)->with(['chapter', 'chapter.course', 'pages'])->orderBy('chapters_id')->orderBy('lesson_order')->get();
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
                $lessons = Lesson::where('id', '!=', 0)->with(['chapter', 'chapter.course'])->orderBy('chapters_id')->orderBy('lesson_order')->get();
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
                $lessons = Lesson::whereIn('chapters_id', $chapters)->with(['chapter', 'chapter.course'])->orderBy('chapters_id')->orderBy('lesson_order')->get();
            }
        }

        return view('lesson.index', [
            "msgs"=>$msgs,
            "lessons"=>$lessons,
            "allChapters"=>$allChapters,
            "chapters_id"=>$chapters_id,
            "user"=>$user,
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
                "chapters"=>$chapters,
                "user"=>$user,
            ]);
        }

        $lesson_order = DB::table('lessons')->where('chapters_id', $request->input('chapters_id'))->max('lesson_order');
        if($lesson_order) {
            $lesson_order++;
        }else {
            $lesson_order = 0;
        }

        $lesson->name = $request->input('name');
        $lesson->description = $request->input('description');
        $lesson->chapters_id = $request->input('chapters_id');
        $lesson->lesson_order = $lesson_order;
        if($user->group_id==0) {
            $lesson->literary_editor = ($request->input('literary_editor')!=null)?1:0;
            $lesson->scientific_editor = ($request->input('scientific_editor')!=null)?1:0;
            $lesson->layout_page_editor = ($request->input('layout_page_editor')!=null)?1:0;
        }
        $lesson->save();
        
        $request->session()->flash('msg_success', 'درس مورد نظر با موفقیت ثبت شد');
        return redirect('/lesson?chapters_id=' . $request->input('chapters_id'));
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
                "chapters"=>$chapters,
                "user"=>$user,
            ]);
        }

        $lesson->name = $request->input('name');
        $lesson->description = $request->input('description');
        $lesson->chapters_id = $request->input('chapters_id');
        if($user->group_id==0) {
            $lesson->literary_editor = ($request->input('literary_editor')!=null)?1:0;
            $lesson->scientific_editor = ($request->input('scientific_editor')!=null)?1:0;
            $lesson->layout_page_editor = ($request->input('layout_page_editor')!=null)?1:0;
        }
        $lesson->save();
        
        $request->session()->flash('msg_success', 'درس مورد نظر با موفقیت بروزسانی شد');
        return redirect('/lesson?chapters_id=' . $request->input('chapters_id'));

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
        if($request->input('sw1')) {
            $order1 = (int)$request->input('sw1');
            $order2 = (int)$request->input('sw2');
            $lessons_id = $id;
            $page1 = Page::where('lessons_id', $lessons_id)->where('page_order', $order1-1)->first();
            $page2 = Page::where('lessons_id', $lessons_id)->where('page_order', $order2-1)->first();
            if(!$page1 || !$page2) {
                return ["status"=>0];
            }

            $page1->page_order = $order2-1;
            $page1->save();

            $page2->page_order = $order1-1;
            $page2->save();

            return ["status"=>1];
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

        $page_order = DB::table('pages')->where('lessons_id', $id)->max('page_order');
        if($page_order) {
            $page_order++;
        }else {
            $page_order = 0;
        }

        $page->lessons_id = $id;
        $thepage = json_decode($request->input('page'));
        $tmpData = $thepage->data;
        $thepage->data = [];
        foreach($tmpData as $i=>$part) {
            if($part->deleted!=true) {
                $thepage->data[] = $part;
            }
        }
        $page->page_order = $page_order;
        $page->save();

        $images = [];
        foreach($request->allFiles() as $key=>$image) {
            $images[$key] = $key . "." . $image->getClientOriginalExtension();
            $image->storeAs('page_images/page_' . $page->id, $key . "." . $image->getClientOriginalExtension());
        }
        foreach($thepage->data as $i=>$part) {
            if($part->type=='image') {
                $thepage->data[$i]->data = $images[$part->data];
            }
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
            if($checkedChoices) {
                foreach($checkedChoices as $checkedChoice) {
                    $tmp = explode('_', $checkedChoice);
                    $tmp = (int)$tmp[1];
                    $realChoices[$tmp]->checked = true;
                }
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
        $question->solution = (trim($request->input('solution'))!='')?trim($request->input('solution')):null;
        $question->save();

        if($request->input('preview')!='0') {
            // $page->load(['lesson', 'question']);
            // return view('lesson.page_create', [
            //     "page"=>$page,
            //     "preview"=>$request->input('preview'),
            // ]);
            $request->session()->flash('preview', $request->input('preview'));
            return redirect('/lesson/page_edit/' . $page->id);
        }

        $request->session()->flash('msg_success', 'صفحه مورد نظر با موفقیت ثبت شد');
        return redirect('/lesson/page/' . $id);
    }

    public function pageEdit(Request $request, $id) {
        $page = Page::find($id);
        if(!$page) {
            $request->session()->flash('msg_danger', 'صفحه مورد نظر پیدا نشد');
            return redirect('/lesson');
        }

        $preview = $request->session()->get('preview');
        if($preview==null) {
            $preview = '0';
        }
        $page->load(['lesson', 'question']);
        if(!$request->isMethod('post')) {
            return view('lesson.page_create', [
                "page"=>$page,
                "preview"=>$preview,
            ]);
        }
        $oldImages = [];
        foreach($page->page->data as $i=>$part) {
            if($part->type=='image') {
                $tmp = explode('.', $part->data);
                $oldImages[$tmp[0]] = $part->data;
            }
        }
        $thepage = json_decode($request->input('page'));
        $tmpData = $thepage->data;
        $thepage->data = [];
        foreach($tmpData as $i=>$part) {
            if($part->deleted!=true) {
                $thepage->data[] = $part;
            }
        }
        $images = [];
        foreach($request->allFiles() as $key=>$image) {
            $images[$key] = $key . "." . $image->getClientOriginalExtension();
            $image->storeAs('page_images/page_' . $id, $key . "." . $image->getClientOriginalExtension());
        }
        foreach($thepage->data as $i=>$part) {
            if($part->type=='image') {
                if(!isset($images[$part->data])) {
                    $thepage->data[$i]->data = $oldImages[$part->data];
                }else {
                    $thepage->data[$i]->data = $images[$part->data];
                }
            }
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
            if($checkedChoices) {
                foreach($checkedChoices as $checkedChoice) {
                    $tmp = explode('_', $checkedChoice);
                    $tmp = (int)$tmp[1];
                    $realChoices[$tmp]->checked = true;
                }
            }
        }

        if($page->question) {
            $page->question()->update([
                "question"=>$request->input('question'),
                "question_type"=>$request->input('question_type'),
                "answer"=>$request->input('answer'),
                "choices"=>\json_encode($realChoices),
                "score"=>$request->input('score'),
                "courses_id"=>$page->lesson->chapter->courses_id,
                "solution"=>(trim($request->input('solution'))!='')?trim($request->input('solution')):null,
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
            $question->solution = (trim($request->input('solution'))!='')?trim($request->input('solution')):null;
            $question->save();
        }

        if($request->input('preview')!='0') {
            $page->load(['lesson', 'question']);
            return view('lesson.page_create', [
                "page"=>$page,
                "preview"=>$request->input('preview'),
            ]);
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
