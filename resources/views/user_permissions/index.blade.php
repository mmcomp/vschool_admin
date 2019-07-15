@extends('layouts.admin')

@php
$access_type = [
  'inc'=>'افزایش',
  'dec'=>'کاهش',
  'incdec'=>'تغییر',
  'viewonly'=>'مشاهده',
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
            لیست دسترسی های وب سرویس
              <small>{{ $user->name }}</small>
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">دسترسی ها</h3>
                          <a class="btn btn-primary pull-left" href="/req/permissions_create/{{ $user->id }}">جدید</a>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                          <table id="example2" class="table table-bordered table-hover table-striped" data-page-length='20'>
                              <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>امتیاز</th>
                                    <th>نوع دسترسی</th>
                                    <th>حداکثر تغییر</th>
                                    <th>وزن</th>
                                    <th>ناحیه</th>
                                    <th>#</th>
                                </tr>
                              </thead>
                              <tbody>
                              @foreach($userProperties as $i=>$userProperty)
                                <tr>
                                  <td>{{ $i + 1 }}</td>
                                  <td>{{ ($userProperty->propertyfield)?$userProperty->propertyfield->field_name:(($userProperty->redisdent_propery_fields_id==-1)?'سکه':(($userProperty->redisdent_propery_fields_id==-2)?'تجربه':'-')) }}</td>
                                  <td>{{ $access_type[$userProperty->access_type] }}</td>
                                  <td>{{ $userProperty->max_change }}</td>
                                  <td>{{ $userProperty->weight }}</td>
                                  <td>{{ ($userProperty->zone)?$userProperty->zone->name:'بدون محدودیت' }}</td>
                                  <td>
                                    <a class="btn btn-primary" href="/req/permissions_time/{{ $userProperty->id }}/{{ $user->id }}">
                                    محدودیت زمانی
                                    </a>
                                    <a class="btn btn-success" href="/req/permissions_edit/{{ $userProperty->id }}/{{ $user->id }}">
                                    ویرایش
                                    </a>
                                    <a class="btn btn-danger btn-delete" href="/req/permissions_delete/{{ $userProperty->id }}/{{ $user->id }}">
                                    حذف
                                    </a>
                                  </td>
                                </tr>
                              @endforeach
                              </tbody>
                              <tfoot>
                                  <tr>
                                    <th>ردیف</th>
                                    <th>امتیاز</th>
                                    <th>نوع دسترسی</th>
                                    <th>حداکثر تغییر</th>
                                    <th>وزن</th>
                                    <th>ناحیه</th>
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