@extends('layouts.admin')

@section('extra_css')

@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
            @if($sequenceDetail->id)
            ویرایش 
            @else
            ثبت
            @endif
            جزئیات چرخه
          </h1>
      </section>

      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">جزئیات چرخه</h3>
                      </div><!-- /.box-header -->
                      <div class="box-body">
                        <form method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="user_id">برنامه</label>
                                        <select class="form-control" id="user_id" name="user_id">
                                            <option disabled>برنامه</option>
                                            @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ ($user->id==$sequenceDetail->user_id)?'selected':'' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="seq_order">ترتیب</label>
                                        <input type="text" class="form-control" id="seq_order" name="seq_order" placeholder="ترتیب" value="{{ ($sequenceDetail && $sequenceDetail->seq_order)?$sequenceDetail->seq_order:'0' }}">
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