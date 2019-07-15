@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($medalian->id)
            ویرایش 
            @else
            ثبت
            @endif
            نشان کاربر
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">نشان</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ ($medalian && $medalian->name)?$medalian->name:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="code">کد جهت استفاده در نرم افزار</label>
                                        <input type="text" class="form-control" id="code" name="code" placeholder="کد جهت استفاده در نرم افزار" value="{{ ($medalian && $medalian->code)?$medalian->code:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="end_date">زمان پایان</label>
                                        <input type="text" class="form-control" id="end_date" name="end_date" placeholder="زمان پایان" value="{{ ($medalian && $medalian->end_date)?$medalian->end_date:'' }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="min_exp">امتیاز</label>
                                        <input type="text" class="form-control" id="min_exp" name="min_exp" placeholder="امتیاز" value="{{ ($medalian && $medalian->min_exp)?$medalian->min_exp:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="start_date">زمان شروع</label>
                                        <input type="text" class="form-control" id="start_date" name="start_date" placeholder="زمان شروع" value="{{ ($medalian && $medalian->start_date)?$medalian->start_date:'' }}">
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