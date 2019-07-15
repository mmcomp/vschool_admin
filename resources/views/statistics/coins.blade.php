@extends('layouts.admin')

@section('extra_css')
<!-- DataTables -->
<link rel="stylesheet" href="/admin/plugins/datatables/dataTables.bootstrap.css">
<!-- Persian Date Picker -->
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.min.css">
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background: url('/admin/dist/img/mashhad.png');">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1 style="background-color: #ffffff;padding: 10px;">
            آمار سکه ها
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                  <h3 class="box-title">آمار سکه ها ی شهروندی</h3>
                  <div class="clearfix" >
                    &nbsp;
                  </div>
                  <form method="post">
                  @csrf
                  <div class="col-xs-5">
                    <input type="text" id="from-date" name="from-date" class="form-control pdate" placeholder="از تاریخ" value="{{ $fromDate }}" />
                  </div>
                  <div class="col-xs-5">
                    <input type="text" id="to-date" name="to-date" class="form-control pdate" placeholder="تا تاریخ" value="{{ $toDate }}" />
                  </div>
                  <div class="col-xs-2">
                    <button class="btn btn-primary">
                    فیلتر
                    </button>
                  </div>
                  </form>
              </div><!-- /.box-header -->
              <div class="box-body">
                <table id="example2" class="table table-bordered table-hover table-striped" data-page-length='20'>
                  <thead>
                    <tr>
                      <th>ردیف</th>
                      <th>نام برنامه</th>
                      <th>میزان افزایش</th>
                      <th>میزان کاهش</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($users as $i=>$user)
                    <tr>
                      <td>{{ $i + 1 }}</td>
                      <td>{{ $user['name'] }}</td>
                      <td>{{ (isset($user['inc']))?$user['inc']:'0' }}</td>
                      <td>{{ (isset($user['dec']))?$user['dec']:'0' }}</td>
                    </tr>
                  @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>ردیف</th>
                      <th>نام برنامه</th>
                      <th>میزان افزایش</th>
                      <th>میزان کاهش</th>
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
<!-- Persian Date Picker -->
<script src="https://unpkg.com/persian-date@latest/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.min.js"></script>
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
  $(".pdate").pDatepicker({
    initialValue: false,
    format: 'YYYY/MM/DD',
  });
</script>
@endsection