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
            مدیریت فصل ها
            <small>Chapters</small>
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">فصل</h3>
                          <a class="btn btn-primary pull-left" href="/chapter/create">ثبت فصل</a>
                          <div>
                            <label>
                              دوره : 
                            </label>
                            <form id="course-selection" method="get" >
                            <select style="width: 400px;" onchange="selectCourse(this);" name="courses_id">
                              <option value="">همه</option>
                              @foreach($allCourses as $course)
                              @if($course->id==$courses_id)
                              <option value="{{ $course->id }}" selected>{{ $course->name }}</option>
                              @else
                              <option value="{{ $course->id }}">{{ $course->name }}</option>
                              @endif
                              @endforeach
                            </select>
                            </form>
                          </div>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                          <table id="example2" class="table table-bordered table-hover table-striped" data-page-length='20'>
                              <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام فصل</th>
                                    <th>دوره</th>
                                    <th>#</th>
                                </tr>
                              </thead>
                              <tbody>
                              @foreach($chapters as $i=>$chapter)
                                <tr>
                                  <td>{{ $i + 1 }}</td>
                                  <td>{{ $chapter->name }}</td>
                                  <td>{{ ($chapter->course)?$chapter->course->name:'' }}</td>
                                  <td>
                                    <a class="btn btn-success" href="/chapter/edit/{{ $chapter->id }}">
                                    ویرایش
                                    </a>
                                    <a class="btn btn-danger btn-delete" href="/chapter/delete/{{ $chapter->id }}">
                                    حذف
                                    </a>
                                  </td>
                                </tr>
                              @endforeach
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th>ردیف</th>
                                  <th>نام فصل</th>
                                  <th>دوره</th>
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
  function selectCourse(dobj) {
    $("#course-selection").submit();
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