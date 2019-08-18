@extends('layouts.admin')


@section('extra_css')
<!-- DataTables -->
<link rel="stylesheet" href="/admin/plugins/datatables/dataTables.bootstrap.css">
<!-- Select2 -->
<link href="/admin/plugins/select2/select2.min.css" rel="stylesheet" />
<style>
  .select2-container {
    width: 100% !important;
  }

  .select2-selection__rendered {
    direction: rtl !important;
  }

  .select2-results__option {
    direction: rtl !important;
    text-align: right !important;
  }
</style>
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background: url('/admin/dist/img/mashhad.png');">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1 style="background-color: #ffffff;padding: 10px;">
            لیست قراردادها
              <small>Protocols</small>
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
                          <h3 class="box-title">قرارداد ها</h3>
                          <div>
                            <form method="post">
                              @csrf
                              <div class="col-md-6">
                                <label>
                                  موضوع
                                </label>
                                <input name="title" placeholder="موضوع" class="form-control" value="{{ isset($req['title'])?$req['title']:'' }}" />
                              </div>
                              <div class="col-md-6">
                                <label>
                                  نوع
                                </label>
                                <select name="type" placeholder="موضوع" class="form-control selecttwo" >
                                  <option value="">همه</option>
                                @foreach($protocolTypes as $i=>$protocolType)
                                  @if(isset($req['type']) && $req['type']==$protocolType->id)
                                  <option selected value="{{ $protocolType->id }}">{{ $protocolType->name }}</option>
                                  @else
                                  <option value="{{ $protocolType->id }}">{{ $protocolType->name }}</option>
                                  @endif
                                @endforeach
                                </select>
                              </div>
                              <div class="col-md-4 col-md-pull-6" style="margin-top: 10px;">
                                <button class="btn btn-success">
                                فیلتر
                                </button>
                              </div>
                            </form>
                          </div>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <div>
                          <a class="btn btn-primary pull-left" href="/protocols/create">
                          ثبت
                          </a>
                        </div>
                        <table id="example2" class="table table-bordered table-hover table-striped" data-page-length='20'>
                            <thead>
                              <tr>
                                <th>ردیف</th>
                                <th>نوع</th>
                                <th>موضوع</th>
                                <th>پیمانکار</th>
                                <th>#</th>
                              </tr>
                            </thead>
                            <tbody>
                            @foreach($protocols as $i=>$protocol)
                              <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $protocol->type->name }}</td>
                                <td>{{ $protocol->title }}</td>
                                <td>{{ $protocol->contractor->name }}</td>
                                <td>
                                  <a target="_blank" class="btn btn-primary" href="/protocoldoc/{{ $protocol->id }}" title="مدارک">
                                    <i class="fas fa-passport"></i>
                                  </a>
                                </td>
                              </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                  <th>ردیف</th>
                                  <th>نوع</th>
                                  <th>موضوع</th>
                                  <th>پیمانکار</th>
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
<!-- Select2 -->
<script src="/admin/plugins/select2/select2.min.js"></script>
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
  $(".selecttwo").select2();
</script>
@endsection