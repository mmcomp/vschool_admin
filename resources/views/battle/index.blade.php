@extends('layouts.admin')

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
            مدیریت نبردها
              <small>Battle</small>
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
                          <h3 class="box-title">نبرد</h3>
                          <a class="btn btn-primary pull-left" href="/battle/create">ثبت نبرد</a>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                          <table id="example2" class="table table-bordered table-hover table-striped" data-page-length='20'>
                              <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام</th>
                                    <th>چرخه</th>
                                    <th>زمان آغاز</th>
                                    <th>زمان پایان</th>
                                    <th>تصویر</th>
                                    <th>#</th>
                                </tr>
                              </thead>
                              <tbody>
                              @foreach($battles as $i=>$battle)
                                <tr>
                                  <td>{{ $i + 1 }}</td>
                                  <td>{{ $battle->name }}</td>
                                  <td>{{ $battle->sequence->name }}</td>
                                  <td>{{ $battle->start_date }}</td>
                                  <td>{{ $battle->end_date }}</td>
                                  @if($battle->image_path && $battle->image_path!='')
                                  <td><img src="{{ $battle->image_path }}" style="height: 20px;" /></td>
                                  @else
                                  <td>-</td>
                                  @endif
                                  <td>
                                    <a class="btn btn-success" href="/battle/edit/{{ $battle->id }}">
                                    ویرایش
                                    </a>
                                    <a class="btn btn-danger btn-delete" href="/battle/delete/{{ $battle->id }}">
                                    حذف
                                    </a>
                                  </td>
                                </tr>
                              @endforeach
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th>ردیف</th>
                                  <th>نام</th>
                                  <th>چرخه</th>
                                  <th>زمان آغاز</th>
                                  <th>زمان پایان</th>
                                  <th>تصویر</th>
                                  <th>#</th>
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