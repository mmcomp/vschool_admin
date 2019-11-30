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
                                    <div class="form-group answers part">
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
                                    </div>
                                    <div class="form-group">
                                        <label for="name">در صورتی که سوال از نوع جای خالی باشد در محل های جای خالی در صورت سوال عبارت ### بگذارید</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">سوال صفحه</label>
                                        <textarea class="form-control" id="question" name="question" >{{ ($page && $page->question)?$page->question->question:'' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">نوع سوال</label>
                                        <select id="question_type" name="question_type"  class="form-control" onchange="showAnswer(this);">
                                            <option value="answer"{{ ($page && $page->question && $page->question->question_type=='answer')?' selected':'' }}>متنی</option>
                                            <option value="choice_question"{{ ($page && $page->question && $page->question->question_type=='choice_question')?' selected':'' }}>چندگزینه</option>
                                            <option value="fill_blank"{{ ($page && $page->question && $page->question->question_type=='fill_blank')?' selected':'' }}>جای خالی</option>
                                        </select>
                                    </div>
                                    @if(($page && $page->question && $page->question->question_type=='answer') || !isset($page->question))
                                    <div class="form-group" id="answer-div">
                                        <label for="name">پاسخ</label>
                                        <textarea class="form-control" name="answer" >{{ ($page && $page->question)?$page->question->answer:'' }}</textarea>
                                    </div>
                                    <div class="form-group" id="answers-div" style="display: none;">
                                        <a class="btn btn-primary" onclick="addAnswer();">
                                        پاسخ
                                        </a>                                    
                                    </div>
                                    @else if($page && $page->question && $page->question->question_type!='answer')
                                    <div class="form-group" id="answers-div">
                                        <a class="btn btn-primary" onclick="addAnswer();">
                                        پاسخ
                                        </a> 
                                        @if($page && $page->question && $page->question->choices)
                                        @foreach($page->question->choices as $i=>$ans)
                                        <div class="form-group answers">
                                            <label for="name">گزینه</label><a class="btn btn-danger pull-left" onclick="removeChoice(this);">X</a>
                                            @if($page->question->question_type!='fill_blank')
                                            <input type="checkbox" class="answers-data-check" name="choices[]" value="choises_{{ $i }}"
                                            @if($ans->checked)
                                            checked
                                            @endif
                                             />
                                            @endif
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
    function updatePage() {
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
        if($("#question_type").val()=='fill_blank') {
            $("#answers-div").append(`<div class="form-group answers">
                    <label for="name">گزینه</label><a class="btn btn-danger pull-left" onclick="removeChoice(this);">X</a>
                    <textarea class="form-control answers-data" name="choises_answers[]" ></textarea>
                </div>`);
        }else {
            $("#answers-div").append(`<div class="form-group answers">
                    <label for="name">گزینه</label><a class="btn btn-danger pull-left" onclick="removeChoice(this);">X</a>
                    <input type="checkbox" class="answers-data-check" name="choices[]" value="choises_${ i }" />
                    <textarea class="form-control answers-data" name="choises_answers[]" ></textarea>
                </div>`);
        }
    }
    function removeChoice(dobj) {
        $(dobj).parent().remove();
        $(".answers-data-check").each(function(id, field) {
            $(field).val(`choises_${id}`);
        });
    }
    function addFormula(id, data) {
        var i = $(".tex").length + 1
        if(!data) {
            data = ''
        }
        $("#end-part").before(`<div class="form-group answers part">
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
        renderText();
    }
    function rotateData () {
        rotateArray();
        renderData();
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