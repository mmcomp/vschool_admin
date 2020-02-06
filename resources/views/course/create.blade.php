@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($course->id)
            ویرایش 
            @else
            ثبت
            @endif
            دوره
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">دوره</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ ($course && $course->name)?$course->name:'' }}">
                                    </div> 

                                    <div class="form-group">
                                        <label for="name">زمان در دوئل</label>
                                        <input type="text" class="form-control" id="duel_time" name="duel_time" placeholder="زمان" value="{{ ($course && $course->duel_time)?$course->duel_time:'' }}">
                                    </div> 

                                    <div class="form-group">
                                        <label for="name">اشتراک ۶ ماهه</label>
                                        <input type="text" class="form-control" id="half_annual_price" name="half_annual_price" placeholder="اشتراک ۶ ماهه" value="{{ ($course && $course->half_annual_price)?$course->half_annual_price:'0' }}">
                                    </div> 
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">توضحیات</label>
                                        <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات" value="{{ ($course && $course->description)?$course->description:'' }}">
                                    </div> 
                                    
                                    <div class="form-group">
                                        <label for="name">اشتراک ماهانه</label>
                                        <input type="text" class="form-control" id="monthly_price" name="monthly_price" placeholder="اشتراک ماهانه" value="{{ ($course && $course->monthly_price)?$course->monthly_price:'0' }}">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="name">اشتراک سالانه</label>
                                        <input type="text" class="form-control" id="annually_price" name="annually_price" placeholder="اشتراک سالانه" value="{{ ($course && $course->annually_price)?$course->annually_price:'0' }}">
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