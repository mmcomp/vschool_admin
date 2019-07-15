@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($rcat->id)
            ویرایش 
            @else
            ثبت
            @endif
            دسته شهروند
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">دسته شهروند</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">دسته شهروندی</label>
                                        <!-- <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="{{ ($rcat && $rcat->name)?$rcat->name:'' }}"> -->
                                        <select name="resident_catagory_id" class="form-control">
                                            @foreach($cats as $cat)
                                                @if($rcat && $rcat->resident_catagory_id && $rcat->resident_catagory_id==$cat->id)
                                                <option value="{{ $cat->id }}" selected>{{ $cat->name }}</option>
                                                @else
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
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