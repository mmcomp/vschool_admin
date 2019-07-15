@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($residentNickname->id)
            ویرایش 
            @else
            ثبت
            @endif
            لقب شهروندی
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">لقب</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ ($residentNickname && $residentNickname->name)?$residentNickname->name:'' }}">
                                    </div>
                            
                                    <div class="form-group">
                                        <label for="resident_property_fields_value">مقدار امتیاز اختصاصی</label>
                                        <input type="text" class="form-control" id="resident_property_fields_value" name="resident_property_fields_value" placeholder="مقدار امتیاز اختصاصی" value="{{ ($residentNickname && $residentNickname->resident_property_fields_value)?$residentNickname->resident_property_fields_value:'0' }}">
                                    </div> 
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="resident_property_fields_id">امتیاز اختصاصی</label>
                                        <select class="form-control" id="resident_property_fields_id" name="resident_property_fields_id">
                                            <option disabled>امتیاز اختصاصی</option>
                                            @foreach($residentPropertyFields as $residentPropertyField)
                                            <option value="{{ $residentPropertyField->id }}" {{ ($residentPropertyField->id==$residentNickname->resident_property_fields_id)?'selected':'' }}>{{ $residentPropertyField->field_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if($residentNickname->id && $residentNickname->image)
                                    <div>
                                        <img src="{{ $residentNickname->image }}" style="max-height: 100px;" />
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="name">تصویر</label>
                                        <input type="file" id="image" name="image">
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