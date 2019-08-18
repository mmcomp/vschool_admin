@extends('layouts.admin')
@php
$entities = [
  'unknown'=>'نامشخص',
  'real'=>'حقیقی',
  'legal'=>'حقوقی'
];
@endphp
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          ثبت قرارداد
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <h3 class="box-title">قرارداد جدید</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
              <form method="post" >
                <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs pull-right">
                    <li class="active"><a href="#tab_1-1" data-toggle="tab" aria-expanded="false">مشخصات قرارداد</a></li>
                    <li class=""><a href="#tab_2-2" data-toggle="tab" aria-expanded="false">انتخاب پیمانکار</a></li>
                    <li class=""><a href="#tab_3-2" data-toggle="tab" aria-expanded="true">تاریخ ها</a></li>
                    <li class=""><a href="#tab_4-2" data-toggle="tab" aria-expanded="true">مبلغ قرارداد و شرایط دریافت</a></li>
                    <li class=""><a href="#tab_5-2" data-toggle="tab" aria-expanded="true">مبلغ قرارداد و شرایط پرداخت</a></li>
                    <li class=""><a href="#tab_6-2" data-toggle="tab" aria-expanded="true">نیروها</a></li>
                    <li class=""><a href="#tab_7-2" data-toggle="tab" aria-expanded="true">مستندات</a></li>
                    <li class=""><a href="#tab_8-2" data-toggle="tab" aria-expanded="true">ضمانت نامه ها</a></li>
                    <li class=""><a href="#tab_9-2" data-toggle="tab" aria-expanded="true">کاردکس مالی</a></li>
                    <li class=""><a href="#tab_10-2" data-toggle="tab" aria-expanded="true">سایر</a></li>
                    <!-- <li class="dropdown">
                      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Dropdown <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
                        <li role="presentation" class="divider"></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
                      </ul>
                    </li> -->
                    <!-- <li class="pull-left header"> مشخصات قرارداد<i class="fa fa-th"></i></li> -->
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab_1-1">
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          شماره قرارداد(شماره دبیرخانه) : 
                          </label>
                          <input name="number" class="form-control" placeholder="شماره" />
                        </div>
                        <div class="col-md-6">
                          <label>
                          نوع خدمت : 
                          </label>
                          <select name="services_id" class="form-control" >
                            <option value="0"></option>
                            @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <label>
                          عنوان قرارداد : 
                          </label>
                          <input name="title" class="form-control" placeholder="عنوان" />
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          شرح خدمت : 
                          </label>
                          <select name="services_descs_id" class="form-control" >
                            <option value="0"></option>
                            @foreach($services_descs as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                          </select>                        
                        </div>
                        <div class="col-md-6">
                          <label>
                          واحد واگذار کننده : 
                          </label>
                          <select name="giving_units_id" class="form-control" >
                            <option value="0"></option>
                            @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          محل ارائه خدمت : 
                          </label>
                          <input name="service_location" class="form-control" placeholder="محل" />
                        </div>
                        <div class="col-md-6">
                          <label>
                          روش واگذاری : 
                          </label>
                          <select name="give_ways_id" class="form-control" >
                            <option value="0"></option>
                            @foreach($give_ways as $gw)
                            <option value="{{ $gw->id }}">{{ $gw->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          شهر محل خدمت : 
                          </label>
                          <select name="cities_id" class="form-control" >
                            <option value="0"></option>
                            @foreach($cities as $gw)
                            <option value="{{ $gw->id }}">{{ $gw->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label>
                          نوع معامله : 
                          </label>
                          <select name="transactions_id" class="form-control" >
                            <option value="0"></option>
                            @foreach($transactions as $gw)
                            <option value="{{ $gw->id }}">{{ $gw->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          نحوه تعیین برنده : 
                          </label>
                          <select name="winner_select_ways_id" class="form-control" >
                            <option value="0"></option>
                            @foreach($winner_select_ways as $gw)
                            <option value="{{ $gw->id }}">{{ $gw->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label>
                          کارفرما : 
                          </label>
                          <select name="employer_company_id" class="form-control" >
                            <option value="0"></option>
                            @foreach($companies as $gw)
                            <option value="{{ $gw->id }}">{{ $gw->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          نماینده کافرما : 
                          </label>
                          <input name="employer_agent" class="form-control" placeholder="عنوان" />
                        </div>
                        <div class="col-md-6">
                          <label>
                          ماهیت قرارداد : 
                          </label>
                          <select name="protocol_types_id" class="form-control" >
                            <option value="0"></option>
                            @foreach($protocol_types as $gw)
                            <option value="{{ $gw->id }}">{{ $gw->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          وضعیت تشریفات: 
                          </label>
                          <select name="formality_statuses_id" class="form-control" >
                            <option value="0"></option>
                            @foreach($formality_statuses as $gw)
                            <option value="{{ $gw->id }}">{{ $gw->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label>
                          نوع تشریفات : 
                          </label>
                          <select name="formality_types_id" class="form-control" >
                            <option value="0"></option>
                            @foreach($formality_types as $gw)
                            <option value="{{ $gw->id }}">{{ $gw->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          تاریخ صورتجلسه کمیسیون معاملات (استعلام - مناقصه -مزایده): 
                          </label>
                          <input name="register" class="form-control pdate" />
                        </div>
                        <div class="col-md-6">
                          <label>
                          متمم قرارداد: 
                          </label>
                          <select name="has_complement" class="form-control" >
                            <option value="no">ندارد</option>
                            <option value="yes">دارد</option>
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          واحد نظارت: 
                          </label>
                          <select name="superviser_units_id" class="form-control" >
                            <option value="0"></option>
                            @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_2-2">
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          نام شرکت 
                          </label>
                          <input name="name" class="form-control" placeholder="نام شرکت" />
                        </div>
                        <div class="col-md-6">
                          <label>
                          نام شخص/مدیرعامل 
                          </label>
                          <input name="fname" class="form-control" placeholder="نام" />
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          نام خانوادگی شخص/مدیرعامل 
                          </label>
                          <input name="lname" class="form-control" placeholder="نام خانوادگی" />
                        </div>
                        <div class="col-md-6">
                          <br/>
                          <a class="btn btn-primary" onclick="searchCompany();" >جستجو</a>
                          <a class="btn btn-success" data-toggle="modal" data-target="#modal-company">جدید</a>
                        </div>
                      </div>
                      <table id="example2" class="table table-bordered table-hover table-striped" data-page-length='20'>
                          <thead>
                            <tr>
                              <th>شماره ثبت</th>
                              <th>نام شرکت</th>
                              <th>نام مدیر</th>
                              <th>کد ملی مدیر</th>
                              <th>آدرس</th>
                              <th>تلفن</th>
                              <th>موبایل</th>
                              <th>استان</th>
                              <th>نام شهر</th>
                              <th>تحصیلات</th>
                              <th>نوع فعالیت</th>
                              <th>مالکیت</th>
                              <th>نوع شخصیت</th>
                              <th>انتخاب</th>
                              <th>ویرایش</th>
                            </tr>
                          </thead>
                          <tbody>
                          @foreach($companies as $i=>$company)
                            <tr>
                              <td>{{ $company->registration_number }}</td>
                              <td>{{ $company->name }}</td>
                              <td>{{ ($company->ceo)?$company->ceo->fname:'' }} {{ ($company->ceo)?$company->ceo->lname:'' }}</td>
                              <td>{{ ($company->ceo)?$company->ceo->national_number:'' }}</td>
                              <td>{{ $company->address }}</td>
                              <td>{{ $company->tells }}</td>
                              <td>{{ ($company->ceo)?$company->ceo->tells:'' }}</td>
                              <td>{{ ($company->city && $company->city->province)?$company->city->province->name:'' }}</td>
                              <td>{{ ($company->city)?$company->city->name:'' }}</td>
                              <td>{{ ($company->ceo && $company->ceo->education)?$company->ceo->education->name:'' }}</td>
                              <td>{{ ($company->service)?$company->service->name:'' }}</td>
                              <td>{{ ($company->ownership)?$company->ownership->name:'' }}</td>
                              <td>{{ $entities[$company->entity] }}</td>
                              <td>
                                <a target="_blank" class="btn btn-primary" href="/companies/select/{{ $company->id }}" title="انتخاب">
                                  <i class="fas fa-check"></i>
                                </a>
                              </td>
                              <td>
                                <a target="_blank" class="btn btn-primary" href="/companies/edit/{{ $company->id }}" title="ویرایش">
                                  <i class="fas fa-pen"></i>
                                </a>
                              </td>
                            </tr>
                          @endforeach
                          </tbody>
                      </table>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_3-2">
                      Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                      Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                      when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                      It has survived not only five centuries, but also the leap into electronic typesetting,
                      remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                      sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                      like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_4-2">
                      Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                      Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                      when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                      It has survived not only five centuries, but also the leap into electronic typesetting,
                      remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                      sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                      like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_5-2">
                      Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                      Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                      when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                      It has survived not only five centuries, but also the leap into electronic typesetting,
                      remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                      sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                      like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_6-2">
                      Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                      Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                      when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                      It has survived not only five centuries, but also the leap into electronic typesetting,
                      remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                      sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                      like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_7-2">
                      Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                      Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                      when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                      It has survived not only five centuries, but also the leap into electronic typesetting,
                      remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                      sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                      like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_8-2">
                      Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                      Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                      when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                      It has survived not only five centuries, but also the leap into electronic typesetting,
                      remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                      sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                      like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_9-2">
                      Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                      Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                      when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                      It has survived not only five centuries, but also the leap into electronic typesetting,
                      remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                      sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                      like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_10-2">
                      Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                      Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                      when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                      It has survived not only five centuries, but also the leap into electronic typesetting,
                      remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                      sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                      like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <!-- /.tab-pane -->
                  </div>
                  <!-- /.tab-content -->
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div> 
  <div class="modal fade" id="modal-company" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">ثبت پیمانکار</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <label>
               شخصیت طرف قرارداد
              </label>
              <select name="entity" class="form-control">
                <option value="unknown">نامشخص</option>
                <option value="real">حقیقی</option>
                <option value="legal">حقوقی</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>
                شماره ثبت شرکت
              </label>
              <input name="registration_number" class="form-control" placeholder="شماره ثبت" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               نام شرکت
              </label>
              <input name="name" class="form-control" placeholder="نام" />
            </div>
            <div class="col-md-6">
              <label>
               نوع فعالیت
              </label>
              <select name="services_id" class="form-control">
                <option value="0"></option>
                @foreach($services as $service)
                <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               نوع شرکت
              </label>
              <select name="ownerships_id" class="form-control">
                <option value="0"></option>
                @foreach($ownerships as $service)
                <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label>
               کد اقتصادی
              </label>
              <input name="economic_code" class="form-control" placeholder="کد اقتصادی" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               مجوز تاسیس دفتر
              </label>
              <input name="office_establishment_license" class="form-control" placeholder="مجوز تاسیس" />
            </div>
            <div class="col-md-6">
              <label>
               شماره گواهینامه
              </label>
              <input name="license_number" class="form-control" placeholder="گواهینامه" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               نام آژانس/کلینیک/دفتر
              </label>
              <input name="agency_name" class="form-control" placeholder="نام دفتر" />
            </div>
            <div class="col-md-6">
              <label>
               شناسه ملی
              </label>
              <input name="national_id" class="form-control" placeholder="شناسه ملی" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               آدرس طرف قرارداد
              </label>
              <input name="address" class="form-control" placeholder="آدرس" />
            </div>
            <div class="col-md-6">
              <label>
               کدپستی
              </label>
              <input name="postal_code" class="form-control" placeholder="کد پستی" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               استان
              </label>
              <select class="form-control" onchange="refreshCities(this)">
                <option value="0">همه</option>
                @foreach($provinces as $service)
                <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label>
               شهر
              </label>
              <select id="cities_id" name="cities_id" class="form-control">
                <option value="0"></option>
                @foreach($cities as $service)
                <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               تلفن ثابت
              </label>
              <input name="tells" class="form-control" placeholder="تلفن ثابت" />
            </div>
            <div class="col-md-6">
              <label>
               تلفن همراه
              </label>
              <input name="mobile" class="form-control" placeholder="تلفن همراه" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               پست الکترونیک
              </label>
              <input name="tells" class="form-control" placeholder="تلفن ثابت" />
            </div>
            <div class="col-md-6">
              <label>
               مدیر عامل
              </label>
              <select name="ceo_agents_id" class="form-control selecttwo">
                <option value="0"></option>
                @foreach($agents as $service)
                <option value="{{ $service->id }}">{{ $service->fname }} {{ $service->lname }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
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
  // $(".selecttwo").select2();
</script>
@endsection