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
                                <form method="post" id="frm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="preview" id="preview" value='0' />
                                    <div class="form-group">
                                        <label for="name">موضوع</label>
                                        <input type="text" class="form-control" id="title" placeholder="موضوع" value="{{ ($page && $page->page && $page->page->title)?$page->page->title:'' }}">
                                    </div>
                                    <input type="hidden" id="page" name="page" />
                                    @if(isset($page->page->data))
                                    @foreach($page->page->data as $id=>$part)
                                    @if($part->type=='image')
                                    @php
                                    $key = explode('.', $part->data);
                                    $key = $key[0];
                                    @endphp
                                    <div class="form-group part">
                                        <label for="name">تصویر </label><button class="btn btn-danger pull-left" onclick="removeNote(this, {{$id}});">X</button>
                                        <img style="height: 100px;" src="/page_images/page_{{ $page->id }}/{{ $part->data }}" />
                                        <input type="file" name="{{ $key }}" class="aimage form-control" accept="image/*" />
                                    </div>
                                    @endif
                                    @if($part->type=='content')
                                    <div class="form-group part">
                                        <label for="name">متن </label><button class="btn btn-danger pull-left" onclick="removeNote(this, {{$id}});">X</button>
                                        <textarea id="content_{{$id}}" onblur="renderText();" class="acontent form-control">{{ $part->data }}</textarea>
                                    </div>
                                    @endif
                                    @if($part->type=='formula')
                                    <div class="form-group part">
                                        <label for="name">فرمول </label><button class="btn btn-danger pull-left" onclick="removeNote(this, {{$id}});">X</button>
                                        <textarea id="formula_{{$id}}" onblur="renderText();" class="tex latex form-control">{{ $part->data }}</textarea>
                                        <span class="formulas">{{ $part->data }}</span>
                                    </div>
                                    @endif
                                    @if($part->type=='note')
                                    <div class="form-group part">
                                        <label for="name">نکته </label><button class="btn btn-danger pull-left" onclick="removeNote(this, {{$id}});">X</button>
                                        <textarea id="note_{{$id}}" class="form-control notes" >{{ isset($part->data)?$part->data:'' }}</textarea>
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif
                                    <div id="end-part" class="form-group">
                                        <label for="name">نوع قسمت</label>
                                        <select id="part"class="form-control">
                                            <option value="content">متن</option>
                                            <option value="formula">فرمول</option>
                                            <option value="note">نکته</option>
                                            <option value="image">تصویر</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <a class="btn btn-primary" onclick="addPart();">
                                        افزودن
                                        </a>
                                        <a class="btn btn-danger" onclick="rotateData();">
                                        چرخش
                                        </a>
                                        <button class="btn btn-success" onclick="updatePage(1);">
                                            پیش نمایش صفحه 
                                        </button>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">در صورتی که سوال از نوع جای خالی باشد در محل های جای خالی در صورت سوال عبارت ### بگذارید</label>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">سوال اول صفحه</label>
                                        <textarea class="form-control" id="question0" name="question0" >{{ ($page && $page->question0)?$page->question0->question:'' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">نوع سوال</label>
                                        <select id="question_type0" name="question_type0"  class="form-control" onchange="showAnswer(this, 0);">
                                            <option value="answer"{{ ($page && $page->question0 && $page->question0->question_type=='answer')?' selected':'' }}>متنی</option>
                                            <option value="choice_question"{{ ($page && $page->question0 && $page->question0->question_type=='choice_question')?' selected':'' }}>چندگزینه</option>
                                            <option value="fill_blank"{{ ($page && $page->question0 && $page->question0->question_type=='fill_blank')?' selected':'' }}>جای خالی</option>
                                        </select>
                                    </div>
                                    @if(($page && $page->question0 && $page->question0->question_type=='answer') || !($page->question0))
                                    <div class="form-group" id="answer-div0">
                                        <label for="name">پاسخ</label>
                                        <textarea class="form-control" name="answer0" >{{ ($page && $page->question0)?$page->question0->answer:'' }}</textarea>
                                    </div>
                                    <div class="form-group" id="answers-div0" style="display: none;">
                                        <a class="btn btn-primary" onclick="addAnswer(0);">
                                        پاسخ
                                        </a>                                    
                                    </div>
                                    @elseif($page && $page->question0 && $page->question0->question_type!='answer')
                                    <div class="form-group" id="answer-div0" style="display: none;">
                                        <label for="name">پاسخ</label>
                                        <textarea class="form-control" name="answer0" >{{ ($page && $page->question0)?$page->question0->answer:'' }}</textarea>
                                    </div>
                                    <div class="form-group" id="answers-div0">
                                        <a class="btn btn-primary" onclick="addAnswer(0);">
                                        پاسخ
                                        </a> 
                                        @if($page && $page->question0 && $page->question0->choices)
                                        @foreach($page->question0->choices as $i=>$ans)
                                        <div class="form-group answers">
                                            <label for="name">گزینه</label><a class="btn btn-danger pull-left" onclick="removeChoice(this, 0);">X</a>
                                            @if($page->question0->question_type!='fill_blank')
                                            <input type="checkbox" class="answers-data-check" name="choices0[]" value="choises0_{{ $i }}"
                                            @if($ans->checked)
                                            checked
                                            @endif
                                             />
                                            @endif
                                            <textarea class="form-control answers-data" name="choises_answers0[]" >{{ $ans->answer }}</textarea>
                                        </div>
                                        @endforeach
                                        @endif                                
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="name">راه حل</label>
                                        <textarea  class="form-control" name="solution0" >{{ ($page && $page->question0 && $page->question0->solution)?$page->question0->solution:'' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">امتیاز</label>
                                        <input type="number" class="form-control" name="score0" placeholder="امتیاز" value="{{ ($page && $page->question0)?$page->question0->score:'0' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="name">سوال دوم صفحه</label>
                                        <textarea class="form-control" id="question1" name="question1" >{{ ($page && $page->question1)?$page->question1->question:'' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">نوع سوال</label>
                                        <select id="question_type1" name="question_type1"  class="form-control" onchange="showAnswer(this, 1);">
                                            <option value="answer"{{ ($page && $page->question1 && $page->question1->question_type=='answer')?' selected':'' }}>متنی</option>
                                            <option value="choice_question"{{ ($page && $page->question1 && $page->question1->question_type=='choice_question')?' selected':'' }}>چندگزینه</option>
                                            <option value="fill_blank"{{ ($page && $page->question1 && $page->question1->question_type=='fill_blank')?' selected':'' }}>جای خالی</option>
                                        </select>
                                    </div>
                                    @if(($page && $page->question1 && $page->question1->question_type=='answer') || !($page->question1))
                                    <div class="form-group" id="answer-div1">
                                        <label for="name">پاسخ</label>
                                        <textarea class="form-control" name="answer1" >{{ ($page && $page->question1)?$page->question1->answer:'' }}</textarea>
                                    </div>
                                    <div class="form-group" id="answers-div1" style="display: none;">
                                        <a class="btn btn-primary" onclick="addAnswer(1);">
                                        پاسخ
                                        </a>                                    
                                    </div>
                                    @elseif($page && $page->question1 && $page->question1->question_type!='answer')
                                    <div class="form-group" id="answer-div1" style="display: none;">
                                        <label for="name">پاسخ</label>
                                        <textarea class="form-control" name="answer1" >{{ ($page && $page->question1)?$page->question1->answer:'' }}</textarea>
                                    </div>
                                    <div class="form-group" id="answers-div1">
                                        <a class="btn btn-primary" onclick="addAnswer(1);">
                                        پاسخ
                                        </a> 
                                        @if($page && $page->question1 && $page->question1->choices)
                                        @foreach($page->question1->choices as $i=>$ans)
                                        <div class="form-group answers">
                                            <label for="name">گزینه</label><a class="btn btn-danger pull-left" onclick="removeChoice(this, 1);">X</a>
                                            @if($page->question1->question_type!='fill_blank')
                                            <input type="checkbox" class="answers-data-check" name="choices1[]" value="choises1_{{ $i }}"
                                            @if($ans->checked)
                                            checked
                                            @endif
                                             />
                                            @endif
                                            <textarea class="form-control answers-data" name="choises_answers1[]" >{{ $ans->answer }}</textarea>
                                        </div>
                                        @endforeach
                                        @endif                                
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="name">راه حل</label>
                                        <textarea  class="form-control" name="solution1" >{{ ($page && $page->question1 && $page->question1->solution)?$page->question1->solution:'' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">امتیاز</label>
                                        <input type="number" class="form-control" name="score1" placeholder="امتیاز" value="{{ ($page && $page->question1)?$page->question1->score:'0' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="name">سوال سوم صفحه</label>
                                        <textarea class="form-control" id="question2" name="question2" >{{ ($page && $page->question2)?$page->question2->question:'' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">نوع سوال</label>
                                        <select id="question_type2" name="question_type2"  class="form-control" onchange="showAnswer(this, 2);">
                                            <option value="answer"{{ ($page && $page->question2 && $page->question2->question_type=='answer')?' selected':'' }}>متنی</option>
                                            <option value="choice_question"{{ ($page && $page->question2 && $page->question2->question_type=='choice_question')?' selected':'' }}>چندگزینه</option>
                                            <option value="fill_blank"{{ ($page && $page->question2 && $page->question2->question_type=='fill_blank')?' selected':'' }}>جای خالی</option>
                                        </select>
                                    </div>
                                    @if(($page && $page->question2 && $page->question2->question_type=='answer') || !($page->question2))
                                    <div class="form-group" id="answer-div2">
                                        <label for="name">پاسخ</label>
                                        <textarea class="form-control" name="answer2" >{{ ($page && $page->question2)?$page->question2->answer:'' }}</textarea>
                                    </div>
                                    <div class="form-group" id="answers-div2" style="display: none;">
                                        <a class="btn btn-primary" onclick="addAnswer(2);">
                                        پاسخ
                                        </a>                                    
                                    </div>
                                    @elseif($page && $page->question2 && $page->question2->question_type!='answer')
                                    <div class="form-group" id="answer-div2" style="display: none;">
                                        <label for="name">پاسخ</label>
                                        <textarea class="form-control" name="answer2" >{{ ($page && $page->question2)?$page->question2->answer:'' }}</textarea>
                                    </div>
                                    <div class="form-group" id="answers-div2">
                                        <a class="btn btn-primary" onclick="addAnswer(2);">
                                        پاسخ
                                        </a> 
                                        @if($page && $page->question2 && $page->question2->choices)
                                        @foreach($page->question2->choices as $i=>$ans)
                                        <div class="form-group answers">
                                            <label for="name">گزینه</label><a class="btn btn-danger pull-left" onclick="removeChoice(this, 2);">X</a>
                                            @if($page->question2->question_type!='fill_blank')
                                            <input type="checkbox" class="answers-data-check" name="choices2[]" value="choises2_{{ $i }}"
                                            @if($ans->checked)
                                            checked
                                            @endif
                                             />
                                            @endif
                                            <textarea class="form-control answers-data" name="choises_answers2[]" >{{ $ans->answer }}</textarea>
                                        </div>
                                        @endforeach
                                        @endif                                
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="name">راه حل</label>
                                        <textarea  class="form-control" name="solution2" >{{ ($page && $page->question2 && $page->question2->solution)?$page->question2->solution:'' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">امتیاز</label>
                                        <input type="number" class="form-control" name="score2" placeholder="امتیاز" value="{{ ($page && $page->question2)?$page->question2->score:'0' }}">
                                    </div>

                                </form>
                            </div>
                            <div class="col-xs-12">
                                <button class="btn btn-success" onclick="updatePage(2);">
                                    پیش نمایش سوال اول
                                </button>
                                <button class="btn btn-success" onclick="updatePage(3);">
                                    پیش نمایش سوال دوم
                                </button>
                                <button class="btn btn-success" onclick="updatePage(4);">
                                    پیش نمایش سوال سوم
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
<!-- <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-chtml.js"></script> -->
<script>
    let dataOrder = [];
    let page_id = -1;
    @if($page && $page->page)
    page_id = {{$page->id}};
    @php
    $theData = [];
    foreach($page->page->data as $part) {
        if($part->type=='image') {
            $key = explode('.', $part->data);
            $part->ext = $key[1];
            $key = $key[0];
            $part->data = $key;
        }
        $theData[] = $part;
    }
    @endphp
    dataOrder = @json($theData);
    @endif
    function addPart() {
        let part = $("#part").val();
        const index = dataOrder.length;
        // console.log(part);
        if(part=='formula') {
            addFormula(index);
        }else if(part == 'note') {
            addNote(index);
        }else if(part == 'content') {
            addContent(index);
        }else if(part == 'image') {
            addImage(index);
        }
        dataOrder.push({
            type: part,
            data: '',
            deleted: false,
        });
    }
    function addImage(id, data, ext) {
        var i = $(".aimage").length + 1
        if(data && page_id>=0) {
            let key = data.split('.')[0]
            $("#end-part").before(`<div class="form-group part">
                <label for="name">تصویر </label><button class="btn btn-danger pull-left" onclick="removeNote(this, ${id});">X</button>
                <img style="height: 100px;" src="/page_images/page_${ page_id }/${ data }.${ ext }" />
                <input type="file" name="${ key }" class="aimage form-control" accept="image/*" />
            </div>`);
        }else {
            $("#end-part").before(`<div class="form-group part">
                <label for="name">تصویر </label><button class="btn btn-danger pull-left" onclick="removeNote(this, ${id});">X</button>
                <input type="file" name="image_${ id }" class="aimage form-control" accept="image/*" />
            </div>`);
        }
    }
    function addContent(id, data) {
        var i = $(".acontent").length + 1
        if(!data) {
            data = ''
        }
        $("#end-part").before(`<div class="form-group part">
                <label for="name">متن </label><button class="btn btn-danger pull-left" onclick="removeNote(this, ${id});">X</button>
                <textarea id="content_${id}" onblur="renderText();" class="acontent form-control">${data}</textarea>
            </div>`);
    }
    function removeNote(dobj, id) {
        if(confirm('آیا حذف انجام شود؟')) {
            dataOrder[id].deleted = true;
            $(dobj).parent().remove();
        }
    }
    function addNote(id, data) {
        var i = $(".notes").length + 1
        if(!data) {
            data = ''
        }
        $("#end-part").before(`<div class="form-group part">
                <label for="name">نکته </label><button class="btn btn-danger pull-left" onclick="removeNote(this, ${id});">X</button>
                <textarea id="note_${id}" class="form-control notes" >${data}</textarea>
            </div>`);
    }
    function updatePage(preview) {
        if($("#question_type").val()=='choice_question') {
            let answerCount = $("div.answers").length
            if(answerCount<2 || answerCount>4) {
                alert('تعداد گزینه های سوال چند گزینه ای باید ۲ تا حداکثر ۵ تا باشد');
                return false;
            }
        }
        let page = {
            title: $("#title").val(),
            data: [],
        };
        for(let i = 0;i < dataOrder.length;i++) {
            if(dataOrder[i].deleted===false) {
                if(dataOrder[i].type!='image') {
                    dataOrder[i].data = $("#" + dataOrder[i].type + "_" + i).val();
                }else if(dataOrder[i].data == ''){
                    dataOrder[i].data = 'image_' + i;
                }
                page.data.push(dataOrder[i]);
            }
        }
        $("#page").val(JSON.stringify(page));
        $("#preview").val(preview);
        if(previewWindow) {
            previewWindow.close();
        }
        $("#frm").submit();
    }
    function showAnswer(dobj, i) {
        var question_type = $(dobj).find('option:selected').val();
        console.log('Question type', question_type);
        if(question_type=='answer') {
            $(".answers" + i).remove();
            $("#answer-div" + i).show();
            $("#answers-div" + i).hide();
        }else {
            $("#answer-div" + i).hide();
            $("#answers-div" + i).show();
        }
    }
    function addAnswer(i) {
        var thei = $(".answers-data-check" + i).length
        if($("#question_type").val()=='fill_blank') {
            $("#answers-div" + i).append(`<div class="form-group answers answers${i}">
                    <label for="name">گزینه</label><a class="btn btn-danger pull-left" onclick="removeChoice(this, ${i});">X</a>
                    <textarea class="form-control answers-data" name="choises_answers${i}[]" ></textarea>
                </div>`);
        }else {
            $("#answers-div" + i).append(`<div class="form-group answers answers${i}">
                    <label for="name">گزینه</label><a class="btn btn-danger pull-left" onclick="removeChoice(this, ${i});">X</a>
                    <input type="checkbox" class="answers-data-check${i}" name="choices${i}[]" value="choises_${ i }" />
                    <textarea class="form-control answers-data" name="choises_answers${i}[]" ></textarea>
                </div>`);
        }
    }
    function removeChoice(dobj, i) {
        $(dobj).parent().remove();
        $(".answers-data-check" + i).each(function(id, field) {
            $(field).val(`choises_${id}`);
        });
    }
    function addFormula(id, data) {
        var i = $(".tex").length + 1
        if(!data) {
            data = ''
        }
        $("#end-part").before(`<div class="form-group part">
                <label for="name">فرمول </label><button class="btn btn-danger pull-left" onclick="removeNote(this, ${id});">X</button>
                <textarea id="formula_${id}" onblur="renderText();" class="tex latex form-control">${data}</textarea>
                <span class="formulas">${data}</span>
            </div>`);
    }
    function renderText() {
        $(".tex").each(function (id, field) {
            var tex = $(field).val();
            $(field).parent().find('span').text(tex);
            MQ.StaticMath($(field).parent().find('span')[0]);
            // let output = $(field).parent().find('span')[0];
            // MathJax.texReset();
            // var options = MathJax.getMetricsFor(output);
            // options.display = true;
            // MathJax.tex2chtmlPromise(tex, options).then(function (node) {
            //     console.log(node);
            //     output.appendChild(node);
            //     MathJax.startup.document.clear();
            //     MathJax.startup.document.updateDocument();
            // }).catch(function (err) {
            //     output.appendChild(document.createElement('pre')).appendChild(document.createTextNode(err.message));
            // }).then(function () {
            //     // finally
            // });
        });
    }
    function rotateArray () {
        let lastElement = dataOrder.pop();
        dataOrder.unshift(lastElement);
        return dataOrder;
    }
    function renderData () {
        $("div.part").remove();
        let i = 0;
        for(let id in dataOrder) {
            i = id;
            if(dataOrder[i].deleted==false) {
                if(dataOrder[i].type=='image') {
                    addImage(id, dataOrder[i].data, dataOrder[i].ext);
                }else if(dataOrder[i].type=='content'){
                    addContent(id, dataOrder[i].data);
                }else if(dataOrder[i].type=='note'){
                    addNote(id, dataOrder[i].data);
                }else if(dataOrder[i].type=='formula'){
                    addFormula(id, dataOrder[i].data);
                }
            }
        }
        renderText();
    }
    function rotateData () {
        for(let i = 0;i < dataOrder.length;i++) {
            if(dataOrder[i].deleted===false) {
                if(dataOrder[i].type!='image') {
                    dataOrder[i].data = $("#" + dataOrder[i].type + "_" + i).val();
                }else if(dataOrder[i].data == ''){
                    dataOrder[i].data = 'image_' + i;
                }
            }
        }
        rotateArray();
        renderData();
    }
    var MQ = MathQuill.getInterface(2);
    var previewWindow;
    $(document).ready(function() {
        $("textarea").blur(function() {
            renderText();
        });
        renderText();
        @if(isset($preview) && $preview=='1')
        previewWindow = window.open('{{ env('APP_URL') }}/preview_page/index.html?id={{ $page->id }}');
        @elseif(isset($preview) && $preview=='2' && $page->question0)
        previewWindow = window.open('{{ env('APP_URL') }}/preview_{{$page->question0->question_type}}/index.html?id={{ $page->question0->id }}');
        @elseif(isset($preview) && $preview=='3' && $page->question1)
        previewWindow = window.open('{{ env('APP_URL') }}/preview_{{$page->question1->question_type}}/index.html?id={{ $page->question1->id }}');
        @elseif(isset($preview) && $preview=='4' && $page->question2)
        previewWindow = window.open('{{ env('APP_URL') }}/preview_{{$page->question2->question_type}}/index.html?id={{ $page->question2->id }}');
        @endif
    });
</script>
@endsection