@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($tournament->id)
            ویرایش 
            @else
            ثبت
            @endif
            چالش
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
                          <h3 class="box-title">چالش</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ ($tournament && $tournament->name)?$tournament->name:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="max_count">تعداد اعضا</label>
                                        <input type="text" class="form-control" id="max_count" name="max_count" placeholder="تعداد اعضا" value="{{ ($tournament && $tournament->max_count)?$tournament->max_count:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="sequences_id">چرخه</label>
                                        <select class="form-control" id="sequences_id" name="sequences_id" >
                                        @foreach($sequences as $sequence)
                                            @if($sequence->id == ($tournament && $tournament->sequences_id)?$tournament->sequences_id:0))
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
                                        <input type="text" class="form-control" id="description" name="description" placeholder="شرح" value="{{ ($tournament && $tournament->description)?$tournament->description:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="limit_date">تاریخ انقضا</label>
                                        <input type="text" class="form-control pdate" id="limit_date" name="limit_date" placeholder="تاریخ انقضا" value="{{ ($tournament && $tournament->limit_date)?$tournament->limit_date:'' }}">
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