@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($teacher->id)
            ویرایش 
            @else
            ثبت
            @endif
            متخصص
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">متخصص</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام</label>
                                        <input type="text" class="form-control" id="fname" name="fname" placeholder="نام" value="{{ ($teacher && $teacher->fname)?$teacher->fname:'' }}">
                                    </div> 

                                    <div class="form-group">
                                        <label for="name">ایمیل</label>
                                        <input type="email" required class="form-control" id="email" name="email" placeholder="ایمیل" value="{{ ($teacher && $teacher->email)?$teacher->email:'' }}">
                                    </div> 
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام خانوادگی</label>
                                        <input type="text" class="form-control" id="lname" name="lname" placeholder="نام خانوادگی" value="{{ ($teacher && $teacher->lname)?$teacher->lname:'' }}">
                                    </div>  

                                    <div class="form-group">
                                        <label for="name">رمز عبور</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="رمز" value="{{ ($teacher && $teacher->password)?$teacher->password:'' }}">
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