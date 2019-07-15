@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($battle->id)
            ویرایش 
            @else
            ثبت
            @endif
            نبرد
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
                          <h3 class="box-title">نبرد</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ ($battle && $battle->name)?$battle->name:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="start_date">تاریخ شروع</label>
                                        <input type="text" class="form-control pdate" id="start_date" name="start_date" placeholder="تاریخ شروع" value="{{ ($battle && $battle->start_date)?$battle->start_date:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="sequences_id">چرخه</label>
                                        <select class="form-control" id="sequences_id" name="sequences_id" >
                                        @foreach($sequences as $sequence)
                                            @if($sequence->id == ($battle && $battle->sequences_id)?$battle->sequences_id:0))
                                            <option value="{{ $sequence->id }}" selected>{{ $sequence->name }}</option>
                                            @else
                                            <option value="{{ $sequence->id }}">{{ $sequence->name }}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="description">شرح</label>
                                        <input type="text" class="form-control" id="description" name="description" placeholder="شرح" value="{{ ($battle && $battle->description)?$battle->description:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="end_date">تاریخ پایان</label>
                                        <input type="text" class="form-control pdate" id="end_date" name="end_date" placeholder="تاریخ پایان" value="{{ ($battle && $battle->end_date)?$battle->end_date:'' }}">
                                    </div>

                                    @if($battle->id && $battle->image_path)
                                    <div>
                                        <img src="{{ $battle->image_path }}" style="max-height: 100px;" />
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="name">تصویر</label>
                                        <input type="file" id="image_path" name="image_path">
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