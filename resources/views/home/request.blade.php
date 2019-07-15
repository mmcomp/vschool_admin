@extends('layouts.admin')
@php
$status = [
    "requested"=>"درخواست داده شده",
    "under_review"=>"در دست بررسی",
    "accepted"=>"قبول شده",
    "rejected"=>"رد شده",
    "suspended"=>"معلق شده",
];
$class = [
    'sport'=>'دسته ورزش و سلامتی',
    'joy'=>'دسته تفریحی',
    'public_trasport'=>'دسته حمل و نقل شهری عمومی',
    'shop'=>'دسته تجاری و فروشگاه',
    'food'=>'دسته غذا و رستوران',
    'health'=>'دسته بهداشت و پزشکی',
    'colture'=>'دسته فرهنگی (مجلات، سینما و ...)',
    'event'=>' دسته رویدادی',
    'trasport'=>'دسته حمل و نقل شهری خصوصی',
    'social'=>'دسته فرهنگ محلات و خانواده',
    'building'=>'دسته ساختمان و عوارض شهری',
    'education'=>'دسته آموزش شهروندی',
    'art'=>'دسته هنر',
    'other'=>'نامشخص'
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
            ویرایش درخواست
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
                          <h3 class="box-title">درخواست</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">نام نرم افزار</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="نام نرم افزار" value="{{ ($user && $user->name)?$user->name:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="national_id">شناسه ملی</label>
                                        <input type="text" class="form-control" id="national_id" name="national_id" placeholder="شناسه ملی" value="{{ ($user && $user->national_id)?$user->national_id:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="register_id">شماره ثبت</label>
                                        <input type="text" class="form-control" id="register_id" name="register_id" placeholder="شماره ثبت" value="{{ ($user && $user->register_id)?$user->register_id:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="tell">تلفن ثابت</label>
                                        <input type="text" class="form-control" id="tell" name="tell" placeholder="تلفن ثابت" value="{{ ($user && $user->tell)?$user->tell:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="website">سایت</label>
                                        <input type="text" class="form-control" id="website" name="website" placeholder="سایت" value="{{ ($user && $user->website)?$user->website:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="email">ایمیل</label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="ایمیل" value="{{ ($user && $user->email)?$user->email:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="encouragement_use_multiplier">ضریب استفاده</label>
                                        <input type="number" class="form-control" id="encouragement_use_multiplier" name="encouragement_use_multiplier" placeholder="ضریب" value="{{ ($user && $user->encouragement_use_multiplier)?$user->encouragement_use_multiplier:'0' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="status">دسته بندی</label>
                                        <select class="form-control" id="status" name="class">
                                            <option disabled>دسته بندی</option>
                                            @foreach($class as $key=>$value)
                                            <option value="{{ $key }}" {{ ($key==$user->class)?'selected':'' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="company_name">نام شرکت</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="نام شرکت" value="{{ ($user && $user->company_name)?$user->company_name:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="open_date">تاریخ تاسیس</label>
                                        <input type="text" class="form-control" id="open_date" name="open_date" placeholder="تاریخ تاسیس" value="{{ ($user && $user->open_date)?$user->open_date:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="mobile">موبایل</label>
                                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="موبایل" value="{{ ($user && $user->mobile)?$user->mobile:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="address">آدرس</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="آدرس" value="{{ ($user && $user->address)?$user->address:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="ip">IP</label>
                                        <input type="text" class="form-control" id="ip" name="ip" placeholder="IPs" value="{{ ($user && $user->ip)?$user->ip:'' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="status">وضعیت</label>
                                        <select class="form-control" id="status" name="status">
                                            <option disabled>وضعیت</option>
                                            @foreach($status as $key=>$value)
                                            <option value="{{ $key }}" {{ ($key==$user->status)?'selected':'' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="experience_multiplier">ضریب نفوذ در تجربه شهروندی</label>
                                        <input type="number" class="form-control" id="experience_multiplier" name="experience_multiplier" placeholder="ضریب نفوذ در تجربه شهروندی" value="{{ ($user && $user->experience_multiplier)?$user->experience_multiplier:'0' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="experience_multiplier">ضریب پیشرفت مشارکت</label>
                                        <input type="number" class="form-control" id="progress_multiplier" name="progress_multiplier" placeholder="ضریب پیشرفت مشارکت" value="{{ ($user && $user->progress_multiplier)?$user->progress_multiplier:'0' }}">
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