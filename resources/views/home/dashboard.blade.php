@extends('layouts.admin')

  @section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          داشبورد
          <small>Dashboard</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Info boxes -->
        <div class="row">
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">پرداخت</span>
                <span class="info-box-number">۹۰<small>%</small></span>
              </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
          </div><!-- /.col -->
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">قرارداد</span>
                <span class="info-box-number">۱۲۰۰</span>
              </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
          </div><!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix visible-sm-block"></div>

          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">از قبل</span>
                <span class="info-box-number">۷۶۰</span>
              </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
          </div><!-- /.col -->
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">امسال</span>
                <span class="info-box-number">۱۰۳</span>
              </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
          </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">پرداخت ها</h3>
                <div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <!-- <div class="btn-group">
                    <button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i
                        class="fa fa-wrench"></i></button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#">Action</a></li>
                      <li><a href="#">Another action</a></li>
                      <li><a href="#">Something else here</a></li>
                      <li class="divider"></li>
                      <li><a href="#">Separated link</a></li>
                    </ul>
                  </div> -->
                  <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div><!-- /.box-header -->
              <div class="box-body">
                <div class="row">
                  <div class="col-md-8">
                    <p class="text-center">
                      <strong>پرداخت ها از ابتدای سال</strong>
                    </p>
                    <div class="chart">
                      <!-- Sales Chart Canvas -->
                      <canvas id="salesChart" style="height: 180px;"></canvas>
                    </div><!-- /.chart-responsive -->
                  </div><!-- /.col -->
                  <div class="col-md-4">
                    <p class="text-center">
                      <strong>پیشرفت مالی قراردادها</strong>
                    </p>
                    <div class="progress-group">
                      <span class="progress-text"> قرارداد نرم افزار رسیدگی </span>
                      <span class="progress-number"><b>۵</b>/۲۰ &nbsp; &nbsp; </span>
                      <div class="progress sm">
                        <div class="progress-bar progress-bar-aqua" style="width: 25%"></div>
                      </div>
                    </div><!-- /.progress-group -->
                    <div class="progress-group">
                      <span class="progress-text">قرارداد تستی ۱</span>
                      <span class="progress-number"><b>۱۰۰</b>/۲۰۰ &nbsp; &nbsp; </span>
                      <div class="progress sm">
                        <div class="progress-bar progress-bar-red" style="width: 50%"></div>
                      </div>
                    </div><!-- /.progress-group -->
                    <div class="progress-group">
                      <span class="progress-text">قرارداد تستی ۲</span>
                      <span class="progress-number"><b>۴۸۰</b>/۸۰۰ &nbsp; &nbsp; </span>
                      <div class="progress sm">
                        <div class="progress-bar progress-bar-green" style="width: 60%"></div>
                      </div>
                    </div><!-- /.progress-group -->
                    <div class="progress-group">
                      <span class="progress-text">قرارداد تستی ۳</span>
                      <span class="progress-number"><b>۲۷۰</b>/۵۰۰ &nbsp; &nbsp; </span>
                      <div class="progress sm">
                        <div class="progress-bar progress-bar-yellow" style="width: 54%"></div>
                      </div>
                    </div><!-- /.progress-group -->
                  </div><!-- /.col -->
                </div><!-- /.row -->
              </div><!-- ./box-body -->
              <div class="box-footer">
                <div class="row">
                  <div class="col-sm-3 col-xs-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> ۱۷%</span>
                      <h5 class="description-header">۳۵۰.۰۰۰.۰۰۰ ریال</h5>
                      <span class="description-text">قراردادهای خدماتی</span>
                    </div><!-- /.description-block -->
                  </div><!-- /.col -->
                  <div class="col-sm-3 col-xs-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> ۰%</span>
                      <h5 class="description-header">۰ ریال</h5>
                      <span class="description-text">قراردادهای نرم افزاری</span>
                    </div><!-- /.description-block -->
                  </div><!-- /.col -->
                  <div class="col-sm-3 col-xs-6">
                    <div class="description-block border-right">
                      <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> ۲۰%</span>
                      <h5 class="description-header">۲۴.۵۶۰.۴۵۰ریال</h5>
                      <span class="description-text">قراردادهای ساختمانی</span>
                    </div><!-- /.description-block -->
                  </div><!-- /.col -->
                  <div class="col-sm-3 col-xs-6">
                    <div class="description-block">
                      <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> ۱۸%</span>
                      <h5 class="description-header">۵۶۰.۸۹۶.۸۸۸ریال</h5>
                      <span class="description-text">قراردادهای اداری</span>
                    </div><!-- /.description-block -->
                  </div>
                </div><!-- /.row -->
              </div><!-- /.box-footer -->
            </div><!-- /.box -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
  @endsection

  @section('extra_script')
  <!-- ChartJS 1.0.1 -->
  <script src="/admin/plugins/chartjs/Chart.min.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="/admin/dist/js/pages/dashboard2.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="/admin/dist/js/demo.js"></script>
  @endsection