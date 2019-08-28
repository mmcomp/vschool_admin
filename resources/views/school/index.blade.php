@extends('layouts.admin')

@section('extra_css')
<!-- DataTables -->
<link rel="stylesheet" href="/admin/plugins/datatables/dataTables.bootstrap.css">
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background: url('/admin/dist/img/vschool_back.jpg');">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1 style="background-color: #ffffff;padding: 10px;">
            مدیریت  مدارس
            <small>Schools</small>
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">مدرسه</h3>
                          <a class="btn btn-primary pull-left" href="/school/create">ثبت مدرسه</a>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                          <table id="example2" class="table table-bordered table-hover table-striped" data-page-length='20'>
                              <thead>
                                <tr>
                                  <th>ردیف</th>
                                  <th>نام</th>
                                  <th>ناحیه</th>
                                  <th>کد</th>
                                  <th>مدیر</th>
                                  <th>#</th>
                                </tr>
                              </thead>
                              <tbody>
                              @foreach($schools as $i=>$school)
                                <tr>
                                  <td>{{ $i + 1 }}</td>
                                  <td>{{ $school->name }}</td>
                                  <td>{{ ($school->zone)?$school->zone->name . ' - ' . $school->zone->city->name . ' - ' . $school->zone->city->province->name:'' }}</td>
                                  <td>{{ $school->code }}</td>
                                  <td>{{ $school->manager_name }}</td>
                                  <td>
                                    <a class="btn btn-success" href="/school/edit/{{ $school->id }}">
                                    ویرایش
                                    </a>
                                    <a class="btn btn-danger btn-delete" href="/school/delete/{{ $school->id }}">
                                    حذف
                                    </a>
                                  </td>
                                </tr>
                              @endforeach
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th>ردیف</>
                                  <th>نام</th>
                                  <th>ناحیه</th>
                                  <th>کد</th>
                                  <th>مدیر</th>
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