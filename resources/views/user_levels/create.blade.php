@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($level->id)
            ویرایش 
            @else
            ثبت
            @endif
            سطح کاربر
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">سطح</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ ($level && $level->name)?$level->name:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="max_exp">حداکثر امتیاز تجربه</label>
                                        <input type="text" class="form-control" id="max_exp" name="max_exp" placeholder="حداکثر امتیاز تجربه" value="{{ ($level && $level->max_exp)?$level->max_exp:'' }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="min_exp">حداقل امتیاز تجربه</label>
                                        <input type="text" class="form-control" id="min_exp" name="min_exp" placeholder="حداقل امتیاز تجربه" value="{{ ($level && $level->min_exp)?$level->min_exp:'' }}">
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