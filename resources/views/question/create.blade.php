@extends('layouts.admin')

@section('extra_css')
<link rel="stylesheet" href="/admin/dist/css/mathquill.css">
<style>
    span.formulas {
        direction: ltr;
    }
    textarea.tex {
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
            @if($question->id)
            ویرایش 
            @else
            ثبت
            @endif
            سوال
          </h1>

          <a class="pull-left" target="_blank" href="https://artofproblemsolving.com/wiki/index.php/LaTeX:Symbols">
            راهنمای فرمول نویسی
          </a><br/>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">سوال</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <!-- <div class="form-group">
                                    <button class="btn btn-primary" onclick="addFormula();">
                                    فرمول
                                    </button>
                                </div> -->
                                <div class="form-group" id="formulas-div">
                                @if($question && isset($question->formulas))
                                @foreach($question->formulas as $i => $formula)
                                    <!-- <div class="form-group">
                                        <label for="name">{{ $i+1 }}</label><button class="btn btn-danger pull-left" onclick="removeNote(this);">X</button>
                                        <textarea class="tex latex form-control">{{ $formula }}</textarea>
                                        <span class="formulas">{{ $formula }}</span>
                                    </div> -->
                                @endforeach
                                @endif
                                </div>
                                <form method="post" id="frm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="preview" id="preview" value='false' />
                                    <div class="form-group">
                                        <label for="name">در صورتی که سوال از نوع جای خالی باشد در محل های جای خالی در صورت سوال یک کارکتر * بگذارید</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">صورت سوال</label>
                                        <textarea class="form-control" id="question" name="question" >{{ ($question && $question->question)?$question->question:'' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">نوع سوال</label>
                                        <select name="question_type"  class="form-control" onchange="showAnswer(this);">
                                            <option value="answer"{{ ($question && $question->question_type=='answer')?' selected':'' }}>متنی</option>
                                            <option value="choice_question"{{ ($question && $question->question_type=='choice_question')?' selected':'' }}>چندگزینه</option>
                                            <option value="fill_blank"{{ ($question && $question->question_type=='fill_blank')?' selected':'' }}>جای خالی</option>
                                        </select>
                                    </div>
                                    @if(($question && $question->question_type=='answer') || (!isset($question->id)))
                                    <div class="form-group" id="answer-div">
                                        <label for="name">پاسخ</label>
                                        <textarea class="form-control" name="answer" >{{ ($question)?$question->answer:'' }}</textarea>
                                    </div>
                                    <div class="form-group" id="answers-div" style="display:none;">
                                        <a class="btn btn-primary" onclick="addAnswer();">
                                        پاسخ
                                        </a> 
                                    </div>
                                    @elseif($question && $question->question_type!='answer')
                                    <div class="form-group" id="answer-div" style="display:none;">
                                        <label for="name">پاسخ</label>
                                        <textarea class="form-control" name="answer" >{{ ($question)?$question->answer:'' }}</textarea>
                                    </div>
                                    <div class="form-group" id="answers-div">
                                        <a class="btn btn-primary" onclick="addAnswer();">
                                        پاسخ
                                        </a> 
                                        @if($question && $question->choices)
                                        @foreach($question->choices as $i=>$ans)
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
                                        <label for="name">راه حل</label>
                                        <textarea  class="form-control" name="solution" >{{ ($question && $question->solution)?$question->solution:'' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">امتیاز</label>
                                        <input type="number" class="form-control" name="score" placeholder="امتیاز" value="{{ ($question && $question->score)?$question->score:'0' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">فصل</label>
                                        <select class="form-control" name="chapters_id" >
                                            <option disable>همه</option>
                                            @foreach($chapters as $chapter)
                                            @if($question && $question->chapters_id && $question->chapters_id==$chapter->id)
                                            <option value="{{ $chapter->id }}" selected>{{ $chapter->name }}</option>
                                            @else
                                            <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    @if($user->group_id==0)
                                    <div class="form-group">
                                        <label for="name">ویراستاری</label><br/>
                                        <p style="color: red;">
                                            ادبی <input type="checkbox" id="literary_editor" name="literary_editor" value="literary_editor" {{ ($question && $question->literary_editor==1)?'checked':'' }} /><br/>
                                        </p>
                                        <p style="color: green;">
                                            علمی <input type="checkbox" id="scientific_editor" name="scientific_editor" value="scientific_editor" {{ ($question && $question->scientific_editor==1)?'checked':'' }} /><br/>
                                        </p>
                                        <p style="color: blue;">
                                            صفحه آرایی <input type="checkbox" id="layout_page_editor" name="layout_page_editor" value="layout_page_editor" {{ ($question && $question->layout_page_editor==1)?'checked':'' }} /><br/>
                                        </p>
                                    </div>
                                    @endif  
                                </form>
                            </div>
                            <div class="col-xs-12">
                            <button class="btn btn-success" onclick="updatePage(true);">
                                پیش نمایش
                            </button>
                            
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
    function updatePage(preview) {
        if($("#question_type").val()=='choice_question') {
            let answerCount = $("div.answer").length
            if(answerCount<2 || answerCount>4) {
                alert('تعداد گزینه های سوال چند گزینه ای باید ۲ تا حداکثر ۵ تا باشد');
                return false;
            }
        }
        // var formulas = []
        // $(".tex").each(function(id, field) {
        //     formulas.push(field.value);
        // });
        // console.log(formulas);
        // $("#formulas").val(JSON.stringify(formulas));
        if(preview===true) {
            $("#preview").val('true');
        }else {
            $("#preview").val('false');
        }
        if(previewWindow) {
            previewWindow.close();
        }
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
    var previewWindow;
    $(document).ready(function() {
        $("textarea").blur(function() {
            renderText();
        });
        renderText();
        @if(isset($preview))
        previewWindow = window.open('{{ env('APP_URL') }}/preview_{{ $question->question_type }}/index.html?id={{ $question->id }}');
        @endif
    });
</script>
@endsection