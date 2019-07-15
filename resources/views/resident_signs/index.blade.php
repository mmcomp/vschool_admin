@extends('layouts.admin')
@php
$i = 0;
@endphp
@section('extra_css')
<!-- DataTables -->
<link rel="stylesheet" href="/admin/plugins/datatables/dataTables.bootstrap.css">
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background: url('/admin/dist/img/mashhad.png');">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1 style="background-color: #ffffff;padding: 10px;">
            نشان های شهروندی شما
              <small>Signs</small>
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">نشان ها</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                          <table id="example2" class="table table-bordered table-hover table-striped" data-page-length='20'>
                              <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام</th>
                                    <th>توضیحات</th>
                                    <th>شروع</th>
                                    <th>پایان</th>
                                    <th>امتیاز اختصاصی</th>
                                    <th>امتیاز تجربه</th>
                                    <th>تصویر</th>
                                    <th>%</th>
                                </tr>
                              </thead>
                              <tbody>
                              @foreach($residentSigns as $rsign)
                                <tr>
                                  <td>{{ $i + 1 }}</td>
                                  <td>{{ $rsign->sign->name }}</td>
                                  <td>{{ $rsign->sign->description }}</td>
                                  <td>{{ $rsign->sign->pstart_date }}</td>
                                  <td>{{ $rsign->sign->pend_date }}</td>
                                  <td>100%</td>
                                  <td>100%</td>
                                  <td>
                                  @if($rsign->sign->image_path && $rsign->sign->image_path!='')
                                    <img src="{{ $rsign->sign->image_path }}" style="height: 30px;" />
                                  @else
                                    -
                                  @endif
                                  </td>
                                  <td>
                                    <div class="progress">
                                      <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                        <span>100%</span>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                                @php
                                $i++;
                                @endphp
                              @endforeach
                              @foreach($residentSignProgresses as $j=>$rsign)
                                <tr>
                                  <td>{{ $i + 1 }}</td>
                                  <td>{{ $rsign->sign->name }}</td>
                                  <td>{{ $rsign->sign->description }}</td>
                                  <td>{{ $rsign->sign->pstart_date }}</td>
                                  <td>{{ $rsign->sign->pend_date }}</td>
                                  <td>{{ is_float((100*($rsign->resident_property_fields_change)/($rsign->sign->resident_property_fields_change)))?number_format(100*($rsign->resident_property_fields_change)/($rsign->sign->resident_property_fields_change), 2, '.', ''):100*($rsign->resident_property_fields_change)/($rsign->sign->resident_property_fields_change) }}%</td>
                                  <td>{{ is_float((100*($rsign->exp_score_change)/($rsign->sign->exp_score_change)))?number_format(100*($rsign->exp_score_change)/($rsign->sign->exp_score_change), 2, '.', ''):100*($rsign->exp_score_change)/($rsign->sign->exp_score_change) }}%</td>            
                                  <td>
                                  @if($rsign->sign->image_path && $rsign->sign->image_path!='')
                                    <img src="{{ $rsign->sign->image_path }}" style="height: 30px;" />
                                  @else
                                    -
                                  @endif
                                  </td>
                                  <td>
                                  @php
                                    $progress = 100*($rsign->resident_property_fields_change + $rsign->exp_score_change)/($rsign->sign->resident_property_fields_change + $rsign->sign->exp_score_change);
                                    if(is_float($progress)) {
                                      $progress = number_format((float)$progress, 2, '.', '');
                                    }
                                    if($progress>100) {
                                      $progress = 100;
                                    }
                                  @endphp
                                    <div class="progress">
                                      <div class="progress-bar progress-bar-{{ ($progress<100)?'yellow':'green' }}" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress }}%">
                                        <span>{{ $progress }}%</span>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                                @php
                                $i++;
                                @endphp
                              @endforeach
                              @foreach($residentOtherSigns as $j=>$rsign)
                                <tr>
                                  <td>{{ $i + 1 }}</td>
                                  <td>{{ $rsign->name }}</td>
                                  <td>{{ $rsign->description }}</td>
                                  <td>{{ $rsign->pstart_date }}</td>
                                  <td>{{ $rsign->pend_date }}</td>
                                  <td>0%</td>
                                  <td>0%</td>
                                  <td>
                                  @if($rsign->image_path && $rsign->image_path!='')
                                    <img src="{{ $rsign->image_path }}" style="height: 30px;" />
                                  @else
                                    -
                                  @endif
                                  </td>
                                  <td>
                                  @php
                                    $progress = 0
                                  @endphp
                                    <div class="progress">
                                      <div class="progress-bar progress-bar-{{ ($progress<100)?'yellow':'green' }}" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress }}%">
                                        <span>{{ $progress }}%</span>
                                      </div>
                                    </div>
                                  </td>
                                </tr>
                                @php
                                $i++;
                                @endphp
                              @endforeach
                              </tbody>
                              <tfoot>
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام</th>
                                    <th>توضیحات</th>
                                    <th>شروع</th>
                                    <th>پایان</th>
                                    <th>امتیاز اختصاصی</th>
                                    <th>امتیاز تجربه</th>
                                    <th>تسویر</th>
                                    <th>%</th>
                                </tr>
                              </tfoot>
                          </table>
                      </div><!-- /.box-body -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
@endsection

@section('alerts')
  @foreach($msgs as $msg)
  <div class="alert alert-{{ $msg['type'] }} alert-dismissable" style="position: fixed;bottom: 10px;left: 10px;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h4>	
          <i class="icon fa fa-{{ $msg['icon'] }}"></i>
      </h4>
      {{ $msg['msg'] }}
  </div>
  @endforeach
@endsection

@section('extra_script')
<!-- DataTables -->
<script src="/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
  $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
  });
  $(".btn-delete").click(function(event) {
    if(!confirm('آیا حذف انجام شود؟')){
      event.preventDefault();
    }
  });
</script>
@endsection