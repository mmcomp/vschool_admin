@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background: url('/admin/dist/img/koohsangi.jpg');">
      <!-- Content Header (Page header) -->
      <section class="content-header" style="background-color: #ffffff;padding: 10px;">
          <h1>
            پروفایل شهروندی
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-md-3">
                <div class="info-box">
                    <!-- Apply any bg-* class to to the icon to color it -->
                    <span class="info-box-icon bg-yellow"><i class="fas fa-coins"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">سکه شهروندی</span>
                        <span class="info-box-number">{{ $resident->coin }}</span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
              </div><!-- /.col -->
              <div class="col-md-3">
                <div class="info-box">
                    <!-- Apply any bg-* class to to the icon to color it -->
                    <span class="info-box-icon bg-red"><i class="fas fa-star-half-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">امتیاز شهروندی</span>
                        <span class="info-box-number">{{ $resident->residet_experience }}</span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
              </div><!-- /.col -->
              <div class="col-md-3">
                <div class="info-box">
                    <!-- Apply any bg-* class to to the icon to color it -->
                    <span class="info-box-icon bg-perpule"><i class="fas fa-level-up-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">لول شهروندی</span>
                        <span class="info-box-number">{{ ($resident->level)?$resident->level->name:'-' }}</span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
          <div class="row" style="background-color: #ffffff;margin-bottom: 10px;">
            <div class="col-md-12">
                <h4>
                دسته های شهروندی
                </h4>
            </div>
          </div>
          <div class="row">
            @foreach($resident->catagory as $cat)
            <div class="col-md-3">
                <div class="info-box">
                    <!-- Apply any bg-* class to to the icon to color it -->
                    <span class="info-box-icon bg-green"><i class="fas fa-layer-group"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ $cat->catagory->name }}</span>
                        <span class="info-box-number">
                        </span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
            </div><!-- /.col -->
            @endforeach
          </div>
          <div class="row">
            <div class="col-md-12" style="background-color: #ffffff;margin-bottom: 10px;">
                <h4>
                امتیاز های شهروندی
                </h4>
            </div>
          </div>
          <div class="row">
            @foreach($resident->property as $prop)
            <div class="col-md-3">
                <div class="info-box">
                    <!-- Apply any bg-* class to to the icon to color it -->
                    <span class="info-box-icon bg-blue"><i class="fas fa-sort-numeric-up"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ $prop->propertyfield->field_name }}</span>
                        <span class="info-box-number">
                        {{ $prop->value }}
                        </span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
            </div><!-- /.col -->
            @endforeach
          </div>
      </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
@endsection

@section('extra_script')
<script>

</script>
@endsection