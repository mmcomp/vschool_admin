@extends('layouts.admin')

@php
$status =[
    "active"=>"فعال",
    "deactive"=>"غیرفعال",
];
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
            مدیریت چرخه ها
              <small>Sequences</small>
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">چرخه ها</h3>
                          <a class="btn btn-primary pull-left" href="/sequences/create">ثبت چرخه</a>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                          <table id="example2" class="table table-bordered table-hover table-striped" data-page-length='20'>
                              <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام</th>
                                    <th>شروع</th>
                                    <th>پایان</th>
                                    <th>وضعیت</th>
                                    <th>کاربران</th>
                                    <th>#</th>
                                </tr>
                              </thead>
                              <tbody>
                              @foreach($sequences as $i=>$sequence)
                                <tr>
                                  <td>{{ $i + 1 }}</td>
                                  <td>{{ $sequence->name }}</td>
                                  <td>{{ $sequence->start_time }}</td>
                                  <td>{{ $sequence->end_time }}</td>
                                  <td>{{ $status[$sequence->status] }}</td>
                                  <td>{{ $sequence->count }}</td>
                                  <td>
                                    <a class="btn btn-primary" href="/sequences/details/{{ $sequence->id }}">
                                    جزئیات
                                    </a>
                                    <a class="btn btn-success" href="/sequences/edit/{{ $sequence->id }}">
                                    ویرایش
                                    </a>
                                    <a class="btn btn-danger btn-delete" href="/sequences/delete/{{ $sequence->id }}">
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
                                    <th>شروع</th>
                                    <th>پایان</th>
                                    <th>وضعیت</th>
                                    <th>کاربران</th>
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