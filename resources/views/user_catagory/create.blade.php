@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($userProperty->id)
            ویرایش 
            @else
            ایجاد
            @endif
            دسته
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">دسته</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="min_exp">حداقل تجربه</label>
                                        <input type="text" class="form-control" id="min_exp" name="min_exp" placeholder="حداقل تجربه" value="{{ ($userProperty && $userProperty->min_exp)?$userProperty->min_exp:'0' }}">
                                    </div>


                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name">دسته شهروندی</label>
                                        <select class="form-control" id="resident_catagories_id" name="resident_catagories_id">
                                            <option disabled>منطقه</option>
                                            @foreach($residentCatagories as $residentCatagorie)
                                            <option value="{{ $residentCatagorie->id }}" {{ ($residentCatagorie->id==$userProperty->resident_catagories_id)?'selected':'' }}>{{ $residentCatagorie->name }}</option>
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