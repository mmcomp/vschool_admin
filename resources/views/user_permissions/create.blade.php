@extends('layouts.admin')

@php
$access_type = [
  'inc'=>'افزایش',
  'dec'=>'کاهش',
  'incdec'=>'تغییر',
  'viewonly'=>'مشاهده',
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
            @if($userProperty->id)
            ویرایش 
            @else
            ایجاد
            @endif
            دسترسی
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">دسترسی</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">امتیاز</label>
                                        <select class="form-control" id="redisdent_propery_fields_id" name="redisdent_propery_fields_id">
                                            <option disabled>امتیاز</option>
                                            @foreach($residentFields as $residentField)
                                            <option value="{{ $residentField->id }}" {{ ($residentField->id==$userProperty->redisdent_propery_fields_id)?'selected':'' }}>{{ $residentField->field_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="weight">وزن</label>
                                        <input type="text" class="form-control" id="weight" name="weight" placeholder="وزن" value="{{ ($userProperty && $userProperty->weight)?$userProperty->weight:'1' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="name">منطقه</label>
                                        <select class="form-control" id="zones_id" name="zones_id">
                                            <option disabled>منطقه</option>
                                            <option value="0">بدون محدودیت</option>
                                            @foreach($zones as $zone)
                                            <option value="{{ $zone->id }}" {{ ($zone->id==$userProperty->zones_id)?'selected':'' }}>{{ $zone->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="status">نوع دسترسی</label>
                                        <select class="form-control" id="access_type" name="access_type">
                                            <option disabled>نوع دسترسی</option>
                                            @foreach($access_type as $key=>$value)
                                            <option value="{{ $key }}" {{ ($key==$userProperty->access_type)?'selected':'' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="max_change">حداکثر تغییرات</label>
                                        <input type="text" class="form-control" id="max_change" name="max_change" placeholder="حداکثر تغییرات" value="{{ ($userProperty && $userProperty->max_change)?$userProperty->max_change:'' }}">
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