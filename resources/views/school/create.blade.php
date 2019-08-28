@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($school->id)
            ویرایش 
            @else
            ثبت
            @endif
            مدرسه
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">مدرسه</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ ($school && $school->name)?$school->name:'' }}">
                                    </div> 
                                    <div class="form-group">
                                        <label for="name">کد مدرسه</label>
                                        <input type="text" class="form-control" id="code" name="code" placeholder="کد" value="{{ ($school && $school->code)?$school->code:'' }}">
                                    </div> 
                                    <div class="form-group">
                                        <label for="name">نام مدیر</label>
                                        <input type="text" class="form-control" id="manager_name" name="manager_name" placeholder="مدیر" value="{{ ($school && $school->manager_name)?$school->manager_name:'' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">نوع مدرسه</label>
                                        <select class="form-control" id="school_type" name="school_type">
                                            <option value="public"{{ ($school && $school->school_type=='public')?' selected':''}}>دولتی</option>
                                            <option value="private"{{ ($school && $school->school_type=='private')?' selected':''}}>غیردولتی</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">تاریخ تاسیس</label>
                                        <input type="text" class="form-control pdate" id="created_date" name="created_date" placeholder="تاسیس" value="{{ ($school && $school->created_date)?$school->created_date:'' }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">منطقه</label>
                                        <select class="form-control" id="zones_id" name="zones_id" >
                                        @foreach($zones as $zone)
                                            @if($school && $school->zones_id==$zone->id)
                                            <option value="{{ $zone->id }}" selected>{{ $zone->name }} - {{ $zone->city->name }} - {{ $zone->city->province->name }}</option>
                                            @else
                                            <option value="{{ $zone->id }}">{{ $zone->name }} - {{ $zone->city->name }} - {{ $zone->city->province->name }}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div> 
                                    <div class="form-group">
                                        <label for="name">آدرس</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="آدرس" value="{{ ($school && $school->address)?$school->address:'' }}">
                                    </div> 
                                    <div class="form-group">
                                        <label for="name">جنسیت مدرسه</label>
                                        <select class="form-control" id="gender" name="gender">
                                            <option value="male"{{ ($school && $school->gender=='male')?' selected':''}}>پسرانه</option>
                                            <option value="female"{{ ($school && $school->gender=='female')?' selected':''}}>دخترانه</option>
                                            <option value="both"{{ ($school && $school->gender=='both')?' selected':''}}>مختلط</option>
                                        </select>
                                    </div> 
                                    <div class="form-group">
                                        <label for="name">دوره مدرسه</label>
                                        <select class="form-control" id="cycle" name="cycle">
                                            <option value="first"{{ ($school && $school->cycle=='first')?' selected':''}}>اول</option>
                                            <option value="second"{{ ($school && $school->cycle=='second')?' selected':''}}>دوم</option>
                                            <option value="both"{{ ($school && $school->cycle=='both')?' selected':''}}>همه</option>
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