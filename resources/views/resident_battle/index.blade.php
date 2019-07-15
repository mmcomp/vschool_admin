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
            نبردها
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">نبرد ها</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover table-striped"
                            data-page-length='20'>
                            <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام</th>
                                    <th>شرح</th>
                                    <th>اتمام</th>
                                    <th>امتیاز شما</th>
                                    <th>امتیاز حریف</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($battles as $i=>$battle)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $battle->name }}</td>
                                    <td>{{ $battle->description }}</td>
                                    <td>{{ $battle->pend_date }}</td>
                                    @if($battle->is_in)
                                        <td>
                                        {{ $battle->you_count }}
                                        </td>
                                        <td>
                                        {{ $battle->fighter_count }}
                                        </td>
                                        <td>
                                        @if($battle->winner_id==$resident->id)
                                        <span style="color: green;">
                                        شما برنده شدید
                                        </span>
                                        @elseif($battle->winner_id>0)
                                        <span style="color: red;">
                                        شما باختیت
                                        </span>
                                        @else
                                        <span style="color: #ef7f71;">
                                        درحال نبرد
                                        </span>
                                        @endif
                                        @if($battle->fighter)
                                          <span style="padding: 10px;background-color: #51a75c;color: #fff;">
                                          {{ $battle->fighter->first_name }}
                                          {{ $battle->fighter->last_name }}
                                          &nbsp;&nbsp;
                                          @if($battle->fighter->image_path)
                                          <img style="height: 30px;" class="img-circle" src="{{ $battle->fighter->image_path }}" />
                                          @else
                                          <img style="height: 30px;" class="img-circle" src="/admin/dist/img/avatar5.png" />
                                          @endif
                                          </span>
                                        @endif
                                        </td>
                                    @else
                                    <td>
                                    -
                                    </td>
                                    <td>
                                    -
                                    </td>
                                    <td>
                                        <a class="btn btn-success" href="/resident_battle/join/{{ $battle->id }}">
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
                                    <th>اتمام</th>
                                    <th>امتیاز شما</th>
                                    <th>امتیاز حریف</th>
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
