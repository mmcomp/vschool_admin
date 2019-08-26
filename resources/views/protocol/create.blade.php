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
              <form method="post" id="main-form" enctype="multipart/form-data">
                @csrf
                <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs pull-right">
                    @if(!$companyAdd)
                    <li class="active"><a href="#tab_1-1" data-toggle="tab" aria-expanded="false">مشخصات قرارداد</a></li>
                    <li class=""><a href="#tab_2-2" data-toggle="tab" aria-expanded="false">انتخاب پیمانکار</a></li>
                    @else
                    <li class=""><a href="#tab_1-1" data-toggle="tab" aria-expanded="false">مشخصات قرارداد</a></li>
                    <li class="active"><a href="#tab_2-2" data-toggle="tab" aria-expanded="false">انتخاب پیمانکار</a></li>
                    @endif
                    <li class=""><a href="#tab_3-2" data-toggle="tab" aria-expanded="true">تاریخ ها</a></li>
                    <li class=""><a href="#tab_4-2" data-toggle="tab" aria-expanded="true">مبلغ قرارداد و شرایط</a></li>
                    <li class=""><a href="#tab_7-2" data-toggle="tab" aria-expanded="true">مستندات</a></li>
                    <!-- <li class=""><a href="#tab_9-2" data-toggle="tab" aria-expanded="true">کاردکس مالی</a></li> -->
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
                    <li class="pull-left header"> مشخصات قرارداد<i class="fa fa-th"></i></li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane
                    @if(!$companyAdd)
                     active
                    @endif" id="tab_1-1">
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
                    <div class="tab-pane
                    @if($companyAdd)
                     active
                    @endif" id="tab_2-2">
                      <input type="hidden" name="contractor_company_id" id="contractor_company_id" value="{{ isset($data['contractor_company_id'])?$data['contractor_company_id']:'0' }}" />
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          نام شرکت 
                          </label>
                          <input name="search_company_name" id="search_company_name" class="form-control" placeholder="نام شرکت" value="{{ isset($data['search_company_name'])?$data['search_company_name']:'' }}" />
                        </div>
                        <div class="col-md-6">
                          <label>
                          نام شخص/مدیرعامل 
                          </label>
                          <input name="search_company_fname" id="search_company_fname" class="form-control" placeholder="نام" value="{{ isset($data['search_company_fname'])?$data['search_company_fname']:'' }}" />
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          نام خانوادگی شخص/مدیرعامل 
                          </label>
                          <input name="search_company_lname" id="search_company_lname" class="form-control" placeholder="نام خانوادگی" value="{{ isset($data['search_company_lname'])?$data['search_company_lname']:'' }}" />
                        </div>
                        <div class="col-md-6">
                          <br/>
                          <input type="hidden" value="" name="is_search" id="is_search" />
                          <input type="hidden" value="" name="company_edit_id" id="company_edit_id" />
                          <button onclick="$('#is_search').val(1);" class="btn btn-primary" >جستجو</button>
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
                              @if($company->id==(isset($data['contractor_company_id'])?(int)$data['contractor_company_id']:0))
                              <td>
                                <a class="btn btn-success"  href="#" title="انتخاب شده">
                                  <i class="fas fa-check-circle"></i>
                                </a>
                              </td>
                              @else
                              <td>
                                <a target="_blank" class="btn btn-primary" onclick="selectCompany({{ $company->id }});return false;" href="#" title="انتخاب">
                                  <i class="fas fa-check"></i>
                                </a>
                              </td>
                              @endif
                              <td>
                                <a target="_blank" class="btn btn-primary"  onclick="editCompany({{ $company->id }});return false;" href="#" title="ویرایش">
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
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          شروع قرارداد
                          </label>
                          <input name="start_date" class="form-control pdate" placeholder="شروع" />
                        </div>
                        <div class="col-md-6">
                          <label>
                          پایان قرارداد
                          </label>
                          <input name="end_date" class="form-control pdate" placeholder="پایان" />
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          تاریخ هشدار
                          </label>
                          <input name="notify_date" class="form-control pdate" placeholder="هشدار" />
                        </div>
                      </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_4-2">
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          از تاریخ
                          </label>
                          <input name="pay_from_date" class="form-control pdate" placeholder="از" />
                        </div>
                        <div class="col-md-6">
                          <label>
                          تا تاریخ
                          </label>
                          <input name="pay_to_date" class="form-control pdate" placeholder="تا" />
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          مبلغ
                          </label>
                          <input name="amount" class="form-control" placeholder="مبلغ" />
                        </div>
                        <div class="col-md-6">
                          <label>
                          واحد ارزی
                          </label>
                          <select name="currency" class="form-control">
                            <option value="rial">ریال</option>
                            <option value="dollar">دلار</option>
                            <option value="euro">یورو</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_7-2">
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          فایل
                          </label>
                          <input type="file" name="file_path[]" class="form-control" multiple />
                        </div>
                        <div class="col-md-6">
                          <label>
                          توضیحات
                          </label>
                          <textarea name="description" class="form-control"></textarea>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <label>
                          تاریخ انقضاء
                          </label>
                          <input name="expire_date" class="form-control pdate" placeholder="انقضاء"/>
                        </div>
                        <div class="col-md-6">
                          <br/>
                          <button class="btn btn-warning">
                          ثبت قرارداد
                          </button>
                        </div>
                      </div>
                    </div>
                    <!-- /.tab-pane -->
                    <!-- <div class="tab-pane" id="tab_9-2">
                    </div> -->
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
        <form method="post" action="company">
        @csrf
        <input type="hidden" name="id" value="{{ ($theCompany)?$theCompany->id:0 }}" />
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <label>
               شخصیت طرف قرارداد
              </label>
              <select name="entity" class="form-control">
                <option value="unknown"{{ ($theCompany && $theCompany->entity=='unknown')?' selected':'' }}>نامشخص</option>
                <option value="real"{{ ($theCompany && $theCompany->entity=='real')?' selected':'' }}>حقیقی</option>
                <option value="legal"{{ ($theCompany && $theCompany->entity=='legal')?' selected':'' }}>حقوقی</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>
                شماره ثبت شرکت
              </label>
              <input name="registration_number" class="form-control" placeholder="شماره ثبت" value="{{ ($theCompany)?$theCompany->registration_number:'' }}" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               نام شرکت
              </label>
              <input name="name" class="form-control" placeholder="نام" value="{{ ($theCompany)?$theCompany->name:'' }}" />
            </div>
            <div class="col-md-6">
              <label>
               نوع فعالیت
              </label>
              <select name="services_id" class="form-control">
                <option value="0"></option>
                @foreach($services as $service)
                @if($theCompany && $theCompany->services_id==$service->id)
                <option value="{{ $service->id }}" selected>{{ $service->name }}</option>
                @else
                <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endif
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
                @if($theCompany && $theCompany->ownerships_id==$service->id)
                <option value="{{ $service->id }}" selected>{{ $service->name }}</option>
                @else
                <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endif
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label>
               کد اقتصادی
              </label>
              <input name="economic_code" class="form-control" placeholder="کد اقتصادی" value="{{ ($theCompany)?$theCompany->economic_code:'' }}" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               مجوز تاسیس دفتر
              </label>
              <input name="office_establishment_license" class="form-control" placeholder="مجوز تاسیس" value="{{ ($theCompany)?$theCompany->office_establishment_license:'' }}" />
            </div>
            <div class="col-md-6">
              <label>
               شماره گواهینامه
              </label>
              <input name="license_number" class="form-control" placeholder="گواهینامه" value="{{ ($theCompany)?$theCompany->license_number:'' }}" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               نام آژانس/کلینیک/دفتر
              </label>
              <input name="agency_name" class="form-control" placeholder="نام دفتر" value="{{ ($theCompany)?$theCompany->agency_name:'' }}" />
            </div>
            <div class="col-md-6">
              <label>
               شناسه ملی
              </label>
              <input name="national_id" class="form-control" placeholder="شناسه ملی" value="{{ ($theCompany)?$theCompany->national_id:'' }}" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               آدرس طرف قرارداد
              </label>
              <input name="address" class="form-control" placeholder="آدرس" value="{{ ($theCompany)?$theCompany->address:'' }}" />
            </div>
            <div class="col-md-6">
              <label>
               کدپستی
              </label>
              <input name="postal_code" class="form-control" placeholder="کد پستی" value="{{ ($theCompany)?$theCompany->postal_code:'' }}" />
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
                @if($theCompany && $theCompany->cities_id==$service->id)
                <option value="{{ $service->id }}" selected>{{ $service->name }}</option>
                @else‍
                <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               تلفن ثابت
              </label>
              <input name="tells" class="form-control" placeholder="تلفن ثابت" value="{{ ($theCompany)?$theCompany->tells:'' }}" />
            </div>
            <div class="col-md-6">
              <label>
               تلفن همراه
              </label>
              <input name="mobile" class="form-control" placeholder="تلفن همراه" value="{{ ($theCompany)?$theCompany->mobile:'' }}" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>
               پست الکترونیک
              </label>
              <input name="email" class="form-control" placeholder="ایمیل" value="{{ ($theCompany)?$theCompany->email:'' }}" />
            </div>
            <div class="col-md-6">
              <label>
               مدیر عامل
              </label>
              <select name="ceo_agents_id" class="form-control selecttwo">
                <option value="0"></option>
                @foreach($agents as $service)
                @if($theCompany && $theCompany->ceo_agents_id==$service->id)
                <option value="{{ $service->id }}" selected>{{ $service->fname }} {{ $service->lname }}</option>
                @else‍
                <option value="{{ $service->id }}">{{ $service->fname }} {{ $service->lname }}</option>
                @endif
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a type="button" class="btn btn-default pull-left" data-dismiss="modal">انصراف</a>
          <button class="btn btn-primary">ثبت</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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
<!-- Select2 -->
<script src="/admin/plugins/select2/select2.min.js"></script>
<!-- DataTables -->
<script src="/admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
  function selectCompany(company_id) {
    $("#is_search").val(1);
    $("#contractor_company_id").val(company_id);
    $("#main-form").submit();
  }
  
  function editCompany(company_id) {
    $("#is_search").val(1);
    $("#company_edit_id").val(company_id);
    $("#main-form").submit();
  }
  $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
  });
  // $(".selecttwo").select2();
  $(document).ready(function() {
    @if($theCompany && $theCompany->id)
    $("#modal-company").modal('show');
    @endif
  });
</script>
@endsection