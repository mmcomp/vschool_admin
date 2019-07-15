@extends('layouts.admin')
@php
$types = [
    'int'=>'عددی',
    'varchar'=>'متن کوتاه',
    'text'=>'متن بلند'
];
@endphp

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($field->id)
            ویرایش 
            @else
            ثبت
            @endif
            امتیاز
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
                          <h3 class="box-title">امتیاز</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="field_name">نام</label>
                                        <input type="text" class="form-control" id="field_name" name="field_name" placeholder="نام" value="{{ ($field && $field->field_name)?$field->field_name:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="default_value">مقدار پیش فرض</label>
                                        <input type="text" class="form-control" id="default_value" name="default_value" placeholder="مقدار پیش فرض" value="{{ ($field && $field->default_value)?$field->default_value:'' }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="field_type">نوع</label>
                                        <select class="form-control" id="field_type" name="field_type">
                                            <option disabled>وضعیت</option>
                                            @foreach($types as $key=>$value)
                                            <option value="{{ $key }}" {{ ($key==$field->field_type)?'selected':'' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
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