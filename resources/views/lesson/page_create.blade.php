@extends('layouts.admin')

@section('extra_css')
<link rel="stylesheet" href="/admin/dist/css/mathquill.css">
<style>
    span.formulas {
        direction: ltr;
    }
</style>
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($page->id)
            ویرایش 
            @else
            ثبت
            @endif
            صفحه
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">صفحه</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="name">موضوع</label>
                                    <input type="text" class="form-control" id="title" name="title" placeholder="موضوع" value="{{ ($page && $page->page && $page->page->title)?$page->page->title:'' }}">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" onclick="addFormula();">
                                    فرمول
                                    </button>
                                </div>

                                <div class="form-group" id="formulas-div">
                                @if($page && $page->page!=null && isset($page->page->formulas))
                                @foreach($page->page->formulas as $i => $formula)
                                <div class="form-group">
                                    <label for="name">{{ $i+1 }}</label><button class="btn btn-danger pull-left" onclick="removeNote(this);">X</button>
                                    <textarea class="tex latex form-control">{{ $formula }}</textarea>
                                    <span class="formulas">{{ $formula }}</span>
                                </div>
                                @endforeach
                                @endif
                                </div>
                                <div class="form-group">
                                    <label for="name">متن اول</label>
                                    <textarea class="form-control" id="content1" name="content1" >{{ ($page && $page->page && $page->page->content1)?$page->page->content1:'' }}</textarea>
                                </div> 

                                <div class="form-group">
                                    <button class="btn btn-primary" onclick="addNote();">
                                    نکته
                                    </button>
                                </div>

                                <div class="form-group" id="notes">
                                @if($page && $page->page!=null )
                                @foreach($page->page->notes as $note)
                                <div class="form-group">
                                    <label for="name">نکته</label><button class="btn btn-danger pull-left" onclick="removeNote(this);">X</button>
                                    <textarea class="form-control notes" >{{ $note }}</textarea>
                                </div>
                                @endforeach
                                @endif
                                </div>

                                <div class="form-group">
                                    <label for="name">متن دوم</label>
                                    <textarea class="form-control" id="content2" name="content2" >{{ ($page && $page->page && $page->page->content2)?$page->page->content2:'' }}</textarea>
                                </div>
                                @if($page && $page->page && $page->page->image)
                                @foreach($page->page->image as $i=>$image)
                                <div class="form-group">
                                    <label>{{ $i+1 }}</label>
                                    <img src="/{{ $image }}" />
                                </div>
                                @endforeach
                                @endif
                                <form method="post" id="frm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="page" id="page" value='' />
                                    <div class="form-group">
                                        <label for="name">تصویر</label>
                                        <input type="file" name="image" />
                                    </div>
                                    <div class="form-group">
                                        <label for="name">در صورتی که سوال از نوع جای خالی باشد در محل های جای خالی در صورت سوال یک کارکتر * بگذارید</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">سوال صفحه</label>
                                        <textarea class="form-control" id="question" name="question" >{{ ($page && $page->question)?$page->question->question:'' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">نوع سوال</label>
                                        <select name="question_type"  class="form-control" onchange="showAnswer(this);">
                                            <option value="answer"{{ ($page && $page->question && $page->question->question_type=='answer')?' selected':'' }}>متنی</option>
                                            <option value="choice_question"{{ ($page && $page->question && $page->question->question_type=='choice_question')?' selected':'' }}>چندگزینه</option>
                                            <option value="fill_blank"{{ ($page && $page->question && $page->question->question_type=='fill_blank')?' selected':'' }}>جای خالی</option>
                                        </select>
                                    </div>
                                    @if($page && $page->question && $page->question->question_type=='answer')
                                    <div class="form-group" id="answer-div">
                                        <label for="name">پاسخ</label>
                                        <textarea class="form-control" name="answer" >{{ ($page && $page->question)?$page->question->answer:'' }}</textarea>
                                    </div>
                                    <div class="form-group" id="answers-div" style="display: none;">
                                        <a class="btn btn-primary" onclick="addAnswer();">
                                        پاسخ
                                        </a>                                    
                                    </div>
                                    @else
                                    <div class="form-group" id="answer-div">
                                        <label for="name">پاسخ</label>
                                        <textarea class="form-control" name="answer" >{{ ($page && $page->question)?$page->question->answer:'' }}</textarea>
                                    </div>
                                    <div class="form-group" id="answers-div">
                                        <a class="btn btn-primary" onclick="addAnswer();">
                                        پاسخ
                                        </a> 
                                        @if($page && $page->question && $page->question->choices)
                                        @foreach($page->question->choices as $i=>$ans)
                                        <div class="form-group answers">
                                            <label for="name">گزینه</label><a class="btn btn-danger pull-left" onclick="removeChoice(this);">X</a>
                                            <input type="checkbox" class="answers-data-check" name="choices[]" value="choises_{{ $i }}"
                                            @if($ans->checked)
                                            checked
                                            @endif
                                             />
                                            <textarea class="form-control answers-data" name="choises_answers[]" >{{ $ans->answer }}</textarea>
                                        </div>
                                        @endforeach
                                        @endif                                
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="name">امتیاز</label>
                                        <input type="number" class="form-control" name="score" placeholder="امتیاز" value="{{ ($page && $page->question)?$page->question->score:'0' }}">
                                    </div>
                                </form>
                            </div>
                            <div class="col-xs-12">
                                <button class="btn btn-primary pull-left" onclick="updatePage();">
                                ذخیره
                                </button>
                            </div>
                        </div>
                      </div><!-- /.box-body -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
@endsection

@section('extra_script')
<script src="/admin/dist/js/mathquill.min.js"></script>
<script>
    function removeNote(dobj) {
        $(dobj).parent().remove();
    }
    function addNote() {
        $("#notes").prepend(`<div class="form-group">
                <label for="name">نکته</label><button class="btn btn-danger pull-left" onclick="removeNote(this);">X</button>
                <textarea class="form-control notes" ></textarea>
            </div>`);
    }
    function updatePage() {
        var page = {
            title: $("#title").val(),
            content1: $("#content1").val(),
            content2: $("#content1").val(),
            notes: [],
            image: [],
            formulas: [],
        }
        $(".notes").each(function(id, field) {
            page.notes.push(field.value);
        });
        $(".tex").each(function(id, field) {
            page.formulas.push(field.value);
        });
        // console.log(page);
        $("#page").val(JSON.stringify(page));
        $("#frm").submit();
    }
    function showAnswer(dobj) {
        var question_type = $(dobj).find('option:selected').val();
        console.log('Question type', question_type);
        if(question_type=='answer') {
            $(".answers").remove();
            $("#answer-div").show();
            $("#answers-div").hide();
        }else {
            $("#answer-div").hide();
            $("#answers-div").show();
        }
    }
    function addAnswer() {
        var i = $(".answers-data-check").length
        $("#answers-div").append(`<div class="form-group answers">
                <label for="name">گزینه</label><a class="btn btn-danger pull-left" onclick="removeChoice(this);">X</a>
                <input type="checkbox" class="answers-data-check" name="choices[]" value="choises_${ i }" />
                <textarea class="form-control answers-data" name="choises_answers[]" ></textarea>
            </div>`);
    }
    function removeChoice(dobj) {
        $(dobj).parent().remove();
        $(".answers-data-check").each(function(id, field) {
            $(field).val(`choises_${id}`);
        });
    }
    function addFormula() {
        var i = $(".tex").length + 1
        $("#formulas-div").append(`<div class="form-group answers">
                <label for="name">${ i }</label><button class="btn btn-danger pull-left" onclick="removeNote(this);">X</button>
                <textarea onblur="renderText();" class="tex latex form-control"></textarea>
                <span class="formulas"></span>
            </div>`);
    }
    function renderText() {
        $(".tex").each(function (id, field) {
            var tex = $(field).val();
            $(field).parent().find('span').text(tex);
            MQ.StaticMath($(field).parent().find('span')[0]);
        });
    }
    var MQ = MathQuill.getInterface(2);
    $(document).ready(function() {
        $("textarea").blur(function() {
            renderText();
        });
        renderText();
    });
</script>
@endsection