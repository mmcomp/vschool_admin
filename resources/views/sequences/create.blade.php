@extends('layouts.admin')

@php
$status =[
    "active"=>"فعال",
    "deactive"=>"غیرفعال",
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
            @if($sequence->id)
            ویرایش 
            @else
            ثبت
            @endif
            چرخه
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">چرخه</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ ($sequence && $sequence->name)?$sequence->name:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="end_time">پایان</label>
                                        <input type="text" class="form-control" id="end_time" name="end_time" placeholder="مثلا :‌21:30:00" value="{{ ($sequence && $sequence->end_time)?$sequence->end_time:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="exp_score">امتیاز تجربه</label>
                                        <input type="text" class="form-control" id="exp_score" name="exp_score" placeholder="امتیاز تجربه" value="{{ ($sequence && $sequence->exp_score)?$sequence->exp_score:'0' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="resident_property_fields_id">امتیاز اختصاصی</label>
                                        <select class="form-control" id="resident_property_fields_id" name="resident_property_fields_id">
                                            <option disabled>امتیاز اختصاصی</option>
                                            <option value="-1">هیچکدام</option>
                                            @foreach($residentPropertyFields as $residentPropertyField)
                                            <option value="{{ $residentPropertyField->id }}" {{ ($residentPropertyField->id==$sequence->resident_property_fields_id)?'selected':'' }}>{{ $residentPropertyField->field_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="start_time">شروع</label>
                                        <input type="text" class="form-control" id="start_time" name="start_time" placeholder="مثلا : 08:00:00" value="{{ ($sequence && $sequence->start_time)?$sequence->start_time:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="status">وضعیت</label>
                                        <select class="form-control" id="status" name="status">
                                            <option disabled>وضعیت</option>
                                            @foreach($status as $key=>$value)
                                            <option value="{{ $key }}" {{ ($key==$sequence->status)?'selected':'' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="coin">سکه</label>
                                        <input type="text" class="form-control" id="coin" name="coin" placeholder="سکه" value="{{ ($sequence && $sequence->coin)?$sequence->coin:'0' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="resident_property_fields_value">مقدار امتیاز اختصاصی</label>
                                        <input type="text" class="form-control" id="resident_property_fields_value" name="resident_property_fields_value" placeholder="مقدار امتیاز اختصاصی" value="{{ ($sequence && $sequence->resident_property_fields_value)?$sequence->resident_property_fields_value:'0' }}">
                                    </div>                                    
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label>
                                    دسته های شهروندی
                                    <br/>
                                    <small>
                                    درصورت عدم انتخاب شامل همه دسته ها خواهد شد
                                    </small>
                                    </label>
                                    <br/>
                                    @foreach($residentCatagories as $residentCatagory)
                                    {{ $residentCatagory->name }}
                                    <input type="checkbox" name="residentCatagory[]" value="{{ $residentCatagory->id }}"
                                    @if(in_array($residentCatagory->id, $sequenceResidentCatagories))
                                    checked
                                    @endif
                                     />
                                    @endforeach
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