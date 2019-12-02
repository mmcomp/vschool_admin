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
            مدیریت سوالات
            <small>{{ $course->name }}</small>
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">صفحه</h3>
                          <a class="btn btn-primary pull-left" href="/question/create/{{ $course->id }}">ثبت سوال</a>
                          @if($user->group_id==0)
                          <div>
                          راهنمای ویراستاری:<br/>
                          ویراستاری ادبی <i title="ویراستاری ادبی" class="fas fa-check" style="color: red;"></i><br/>
                          ویراستاری علمی <i title="ویراستاری علمی" class="fas fa-check" style="color: green;"></i><br/>
                          ویراستاری صفحه آرایی <i title="ویراستاری صفحه آرایی" class="fas fa-check" style="color: blue;"></i>
                          </div>
                          @endif
                      </div><!-- /.box-header -->
                      <div class="box-body">
                          <table id="example2" class="table table-bordered table-hover table-striped" data-page-length='20'>
                              <thead>
                                <tr>
                                  <th>ردیف</th>
                                  <th>موضوع</th>
                                  @if($user->group_id==0)
                                  <th>ویراستاری</th>
                                  <!-- <th>ویراستاری ادبی</th>
                                  <th>ویراستاری علمی</th>
                                  <th>ویراستاری صفحه آرایی</th> -->
                                  @endif
                                  <th>#</th>
                                </tr>
                              </thead>
                              <tbody>
                              @foreach($questions as $i=>$question)
                                <tr>
                                  <td>{{ $i + 1 }}</td>
                                  <td>{{ $question->question }}</td>
                                  @if($user->group_id==0)
                                  <td>
                                    @if($question->literary_editor)
                                    <i title="ویراستاری ادبی" class="fas fa-check" style="color: red;"></i>
                                    @endif
                                    @if($question->scientific_editor)
                                    <i title="ویراستاری علمی" class="fas fa-check" style="color: green;"></i>
                                    @endif
                                    @if($question->layout_page_editor)
                                    <i title="ویراستاری صفحه آرایی" class="fas fa-check" style="color: blue;"></i>
                                    @endif
                                  </td>
                                  @endif
                                  <td>
                                    <a class="btn btn-success" href="/question/edit/{{ $question->id }}">
                                    ویرایش
                                    </a>
                                    <a class="btn btn-danger btn-delete" href="/question/delete/{{ $question->id }}">
                                    حذف
                                    </a>
                                  </td>
                                </tr>
                              @endforeach
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th>ردیف</>
                                  <th>موضوع</th>
                                  @if($user->group_id==0)
                                  <th>ویراستاری</th>
                                  <!-- <th>ویراستاری ادبی</th>
                                  <th>ویراستاری علمی</th>
                                  <th>ویراستاری صفحه آرایی</th> -->
                                  @endif
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
  function selectChapter(dobj) {
    $("#chapter-selection").submit();
  }
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