@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($activity->id)
            ویرایش 
            @else
            ثبت
            @endif
            گروه
          </h1>
          <!-- <ol class="breadcrumb">
              <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
              <li><a href="#">Tables</a></li>
              <li class="active">Data tables</li>
          </ol> -->
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">گروه</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ ($activity && $activity->name)?$activity->name:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="max_count">تعداد اعضا</label>
                                        <input type="text" class="form-control" id="max_count" name="max_count" placeholder="تعداد اعضا" value="{{ ($activity && $activity->max_count)?$activity->max_count:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="coin">سکه شهروندی</label>
                                        <input type="text" class="form-control" id="coin" name="coin" placeholder="سکه شهروندی" value="{{ ($activity && $activity->coin)?$activity->coin:'0' }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="description">شرح</label>
                                        <input type="text" class="form-control" id="description" name="description" placeholder="شرح" value="{{ ($activity && $activity->description)?$activity->description:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="exp">امتیاز تجربه</label>
                                        <input type="text" class="form-control" id="exp" name="exp" placeholder="امتیاز تجربه" value="{{ ($activity && $activity->exp)?$activity->exp:'0' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="join_limit_date">تاریخ انقضا</label>
                                        <input type="text" class="form-control pdate" id="join_limit_date" name="join_limit_date" placeholder="تاریخ انقضا" value="{{ ($activity && $activity->join_limit_date)?$activity->join_limit_date:'' }}">
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