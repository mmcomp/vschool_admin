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
            جدول رده بندی 
            {{ $tournament->name }}
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">جدول رده بندی</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover table-striped"
                            data-page-length='20'>
                            <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>رتبه</th>
                                    <th>تصویر</th>
                                    <th>امتیاز</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tops as $i=>$theResident)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td
                                    @if($theResident->id==$resident->id)
                                    style="color: red;"
                                    @endif
                                    >{{ $i + 1 }}</td>
                                    @if($theResident->image_path && $theResident->image_path!='')
                                    <td><img src="{{ $theResident->image_path }}" style="height: 50px;" /></td>
                                    @else
                                    <td> <img src="/admin/dist/img/avatar5.png" style="height: 50px;" /></td>
                                    @endif
                                    <td>{{ $theResident->score }}</td>
                                </tr>
                                @endforeach
                                @php
                                $indx = count($tops);
                                @endphp
                                @foreach($lows as $i=>$theResident)
                                <tr>
                                    <td>{{ $indx + $i }}</td>
                                    <td
                                    @if($theResident->id==$resident->id)
                                    style="color: red;"
                                    @endif
                                    >{{ $theResident->position }}</td>
                                    @if($theResident->image_path && $theResident->image_path!='')
                                    <td><img src="{{ $theResident->image_path }}" style="height: 50px;" /></td>
                                    @else
                                    <td> <img src="/admin/dist/img/avatar5.png" style="height: 50px;" /></td>
                                    @endif
                                    <td>{{ $theResident->score }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                  <th>ردیف</th>
                                  <th>رتبه</th>
                                  <th>تصویر</th>
                                  <th>امتیاز</th>
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
