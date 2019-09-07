@extends('layouts.admin')

@section('extra_css')
<!-- DataTables -->
<!-- <link rel="stylesheet" href="/admin/plugins/datatables/dataTables.bootstrap.css"> -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/r-2.2.2/rr-1.2.4/datatables.min.css"/>
<style>
  .splash {
    background-color: rgba(245, 230, 244, 0.38);
    position: fixed;
    height: 100%;
    top: 0;
    z-index: 99999;
    width: 100%;
    color: #000000;
    display: none;
  }
  .lesson-index {
    cursor: crosshair;
  }
</style>
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="background: url('/admin/dist/img/vschool_back.jpg');">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1 style="background-color: #ffffff;padding: 10px;">
            مدیریت درس ها
            <small>Lessons</small>
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">درس</h3>
                          <a class="btn btn-primary pull-left" href="/lesson/create">ثبت درس</a>
                          <div>
                            <label>
                              فصل : 
                            </label>
                            <form id="chapter-selection" method="get" >
                            <select style="width: 400px;" onchange="selectChapter(this);" name="chapters_id" id="chapters_id">
                              <option value="">همه</option>
                              @foreach($allChapters as $chapter)
                              @if($chapter->id==$chapters_id)
                              <option value="{{ $chapter->id }}" selected>{{ $chapter->course->name }}->{{ $chapter->name }}</option>
                              @else
                              <option value="{{ $chapter->id }}">{{ $chapter->course->name }}->{{ $chapter->name }}</option>
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
                                  <th>نام</th>
                                  <th>دوره</th>
                                  <th>فصل</th>
                                  <th>#</th>
                                </tr>
                              </thead>
                              <tbody>
                              @foreach($lessons as $i=>$lesson)
                                <tr>
                                  @if($chapters_id!='')
                                  <td class="lesson-index" id="{{ $i + 1 }}">درس {{ $i + 1 }}</td>
                                  @else
                                  <td>{{ $i + 1 }}</td>
                                  @endif
                                  <td>{{ $lesson->name }}</td>
                                  <td>{{ $lesson->chapter->course->name }}</td>
                                  <td>{{ $lesson->chapter->name }}</td>
                                  <td>
                                    <a class="btn btn-primary" href="/lesson/page/{{ $lesson->id }}">
                                    صفحات
                                    [
                                    {{ ($lesson->pages)?count($lesson->pages):0 }}
                                    ]
                                    </a>
                                    <a class="btn btn-success" href="/lesson/edit/{{ $lesson->id }}">
                                    ویرایش
                                    </a>
                                    <a class="btn btn-danger btn-delete" href="/lesson/delete/{{ $lesson->id }}">
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
                                  <th>دوره</th>
                                  <th>فصل</th>
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
  <div class="splash">
    <img src="/admin/dist/img/loading.gif" />
  </div>
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
<!-- <script src="/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="/admin/plugins/datatables/dataTables.rowReorder.js"></script> -->
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/r-2.2.2/rr-1.2.4/datatables.min.js"></script>
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
      "autoWidth": false,
      @if($chapters_id!='')
      "rowReorder": true,
      @endif
  });
  $('#example2').on('row-reordered.dt', function(e) {
    $(".splash").show();
    var sending = false;
    $(".lesson-index").each(function(id, field) {
      var id = $(field).prop('id');
      var data = parseInt($(field).text().split(' ')[1], 10);
      if(id!=data && !sending) {
        sending = true;
        var query = `sw1=${id}&sw2=${data}`;
        var chapters_id = $("#chapters_id").val();
        if(chapters_id!='') {
          query += `&chapters_id=${chapters_id}`;
        }
        $.get(`/lesson?${query}`, function(result) {
          window.location.reload();
        }).fail(function() {
          alert('خطا در برقراری ارتباط');
          $(".splash").hide();
        });
      }
    });
  });
  $(".btn-delete").click(function(event) {
    if(!confirm('آیا حذف انجام شود؟')){
      event.preventDefault();
    }
  });
</script>
@endsection