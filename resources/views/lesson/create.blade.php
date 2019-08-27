@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($lesson->id)
            ویرایش 
            @else
            ثبت
            @endif
            درس
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">درس</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ ($lesson && $lesson->name)?$lesson->name:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="name">فصل</label>
                                        <select class="form-control" id="chapters_id" name="chapters_id" >
                                        @foreach($chapters as $chapter)
                                            @if($lesson && $lesson->chapters_id==$chapter->id)
                                            <option value="{{ $chapter->id }}" selected>{{ $chapter->course->name }}->{{ $chapter->name }}</option>
                                            @else
                                            <option value="{{ $chapter->id }}">{{ $chapter->course->name }}->{{ $chapter->name }}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">توضحیات</label>
                                        <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات" value="{{ ($lesson && $lesson->description)?$lesson->description:'' }}">
                                    </div>                             
                                </div>
                                <div class="col-xs-12">
                                    <button class="btn btn-primary pull-left">
                                    ذخیره
                                    </button>
                                </div>
                            </div>
                        </form>
                      </div><!-- /.box-body -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
@endsection

@section('extra_script')
<script>

</script>
@endsection