@extends('layouts.admin')

@section('extra_css')

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
                                    <label for="name">متن اول</label>
                                    <textarea class="form-control" id="content1" name="content1" >{{ ($page && $page->page && $page->page->content1)?$page->page->content1:'' }}</textarea>
                                </div> 

                                <div class="form-group">
                                    <button class="btn btn-primary" onclick="addNote();">
                                    نکته
                                    </button>
                                </div>

                                <div class="form-group" id="notes">
                                </div>

                                <div class="form-group">
                                    <label for="name">متن دوم</label>
                                    <textarea class="form-control" id="content2" name="content2" >{{ ($page && $page->page && $page->page->content2)?$page->page->content2:'' }}</textarea>
                                </div>
                                @if($page && $page->page && $page->page->image)
                                <div class="form-group">
                                    <img src="/{{ $page->page->image }}" />
                                </div>
                                @endif
                                <form method="post" id="frm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="page" id="page" value='' />
                                    <div class="form-group">
                                        <label for="name">تصویر</label>
                                        <input type="file" name="image" />
                                    </div>
                                    <div class="form-group">
                                        <label for="name">سوال صفحه</label>
                                        <textarea class="form-control" id="question" name="question" >{{ ($page && $page->question)?$page->question->question:'' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">نوع سوال</label>
                                        <select name="question_type"  class="form-control">
                                            <option value="answer"{{ ($page && $page->question && $page->question->question_type=='answer')?' selected':'' }}>متنی</option>
                                            <option value="choice_question"{{ ($page && $page->question && $page->question->question_type=='choice_question')?' selected':'' }}>چندگزینه</option>
                                            <option value="fill_blank"{{ ($page && $page->question && $page->question->question_type=='fill_blank')?' selected':'' }}>جای خالی</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">پاسخ</label>
                                        <input type="text" class="form-control" name="answer" placeholder="پاسخ" value="{{ ($page && $page->question)?$page->question->answer:'' }}">
                                    </div>
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
<script>
    function addNote() {
        $("#notes").prepend(`<div class="form-group">
                <label for="name">نکته</label>
                <textarea class="form-control notes" ></textarea>
            </div>`);
    }
    function updatePage() {
        var page = {
            title: $("#title").val(),
            content1: $("#content1").val(),
            content2: $("#content1").val(),
            notes: [],
            image: ''
        }
        $(".notes").each(function(id, field) {
            page.notes.push(field.value);
        });
        $("#page").val(JSON.stringify(page));
        $("#frm").submit();
    }
</script>
@endsection