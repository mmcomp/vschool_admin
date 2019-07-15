@extends('layouts.admin')

@section('extra_css')
<!-- DataTables -->
<link rel="stylesheet" href="/admin/plugins/datatables/dataTables.bootstrap.css">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="background: url('/admin/dist/img/koohsangi.jpg');">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="background-color: #ffffff;padding: 10px;">
        <h1>
            چالش های گروهی
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">چالش ها</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover table-striped"
                            data-page-length='20'>
                            <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام</th>
                                    <th>شرح</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tournaments as $i=>$tournament)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $tournament->name }}</td>
                                    <td>{{ $tournament->description }}</td>
                                    @if($tournament->is_in)
                                    <td>
                                        <a class="btn btn-primary" href="/resident_tournament/leader/{{ $tournament->id }}">
                                            <span style="margin-left: 5px;"><i class="fas fa-align-justify"></i></span>
                                            جدول رده بندی
                                        </a>
                                    </td>
                                    @else
                                    <td>
                                        <a class="btn btn-success" href="/resident_tournament/join/{{ $tournament->id }}">
                                        ورود
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام</th>
                                    <th>شرح</th>
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
<script>
    function hidePrvc() {
        $('.prvc').hide();
        return false;
    }

</script>
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
    $(".btn-delete").click(function (event) {
        if (!confirm('آیا حذف انجام شود؟')) {
            event.preventDefault();
        }
    });

</script>
@endsection
