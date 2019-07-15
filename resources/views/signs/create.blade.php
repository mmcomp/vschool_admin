@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($sign->id)
            ویرایش 
            @else
            ثبت
            @endif
            نشان
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
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ ($sign && $sign->name)?$sign->name:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="end_date">پایان</label>
                                        <input type="text" class="form-control" id="end_date" name="end_date" placeholder="مثلا :‌1397/11/22" value="{{ ($sign && $sign->end_date)?$sign->end_date:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="resident_property_fields_id">امتیاز اختصاصی</label>
                                        <select class="form-control" id="resident_property_fields_id" name="resident_property_fields_id">
                                            <option disabled>امتیاز اختصاصی</option>
                                            <option value="-1">هیچکدام</option>
                                            @foreach($residentPropertyFields as $residentPropertyField)
                                            <option value="{{ $residentPropertyField->id }}" {{ ($residentPropertyField->id==$sign->resident_property_fields_id)?'selected':'' }}>{{ $residentPropertyField->field_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="image_path">تصویر</label>
                                        <input type="file" id="image_path" name="image_path" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="start_date">شروع</label>
                                        <input type="text" class="form-control" id="start_date" name="start_date" placeholder="مثلا :‌1397/11/22" value="{{ ($sign && $sign->start_date)?$sign->start_date:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="exp_score_change">تغییر امتیاز تجربه</label>
                                        <input type="text" class="form-control" id="exp_score_change" name="exp_score_change" placeholder="تغییر امتیاز تجربه" value="{{ ($sign && $sign->exp_score_change)?$sign->exp_score_change:'0' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="resident_property_fields_change">مقدار تغییر امتیاز اختصاصی</label>
                                        <input type="text" class="form-control" id="resident_property_fields_change" name="resident_property_fields_change" placeholder="مقدار امتیاز اختصاصی" value="{{ ($sign && $sign->resident_property_fields_change)?$sign->resident_property_fields_change:'0' }}">
                                    </div>  
                                    @if($sign->image_path!='')
                                    <br/>
                                    <img src="{{ $sign->image_path }}" style="height: 100px;" />
                                    @endif                                  
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