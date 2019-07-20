@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($protocolDoc->id)
            ویرایش 
            @else
            ثبت
            @endif
            مدرک قرارداد
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">مدرک قرارداد {{ $protocol->title }}</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">فایل</label>
                                        <input type="file" class="form-control" id="file_path" name="file_path" >
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">توضحیات</label>
                                        <input type="text" class="form-control" id="description" name="description" placeholder="توضیحات" value="{{ ($protocolDoc && $protocolDoc->description)?$protocolDoc->description:'' }}">
                                    </div>    
                                    <div class="form-group">
                                        <label for="expire_date">تاریخ انقضا</label>
                                        <input type="text" class="form-control pdate" id="expire_date" name="expire_date" placeholder="تاریخ انقضا" value="{{ ($protocolDoc && $protocolDoc->expire_date)?$protocolDoc->expire_date:'' }}">
                                    </div>                              
                                </div>
                                <div class="col-xs-12">
                                    <button class="btn btn-primary pull-left">
                                    ذخیره
                                    </button>
                                </div>
                            </div>
                        </form>
                      </div><!-- /.box-body -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
@endsection

@section('extra_script')
<script>

</script>
@endsection