@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($userPropertyTime->id)
            ویرایش 
            @else
            ایجاد
            @endif
            زمان دسترسی
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">زمان دسترسی</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="start_time">زمان شروع</label>
                                        <input type="text" class="form-control" id="start_time" name="start_time" placeholder="زمان شروع" value="{{ ($userPropertyTime && $userPropertyTime->start_time)?$userPropertyTime->start_time:'08:00:00' }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="end_time">زمان پایان</label>
                                        <input type="text" class="form-control" id="end_time" name="end_time" placeholder="زمان پایان" value="{{ ($userPropertyTime && $userPropertyTime->end_time)?$userPropertyTime->end_time:'20:00:00' }}">
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