<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>بازی انگاری</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/admin/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/admin/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
      .gly-spin {
        -webkit-animation: spin 2s infinite linear;
        -moz-animation: spin 2s infinite linear;
        -o-animation: spin 2s infinite linear;
        animation: spin 2s infinite linear;
      }
      @-moz-keyframes spin {
        0% {
          -moz-transform: rotate(0deg);
        }
        100% {
          -moz-transform: rotate(359deg);
        }
      }
      @-webkit-keyframes spin {
        0% {
          -webkit-transform: rotate(0deg);
        }
        100% {
          -webkit-transform: rotate(359deg);
        }
      }
      @-o-keyframes spin {
        0% {
          -o-transform: rotate(0deg);
        }
        100% {
          -o-transform: rotate(359deg);
        }
      }
      @keyframes spin {
        0% {
          -webkit-transform: rotate(0deg);
          transform: rotate(0deg);
        }
        100% {
          -webkit-transform: rotate(359deg);
          transform: rotate(359deg);
        }
      }
    </style>
  </head>
  <body class="register-page" style="direction: rtl;background-image: url(/admin/dist/img/mashhad.png);background-position: center;background-repeat: no-repeat;background-size: cover;">
    @if($msg!='')
    <div class="alert alert-{{ $msg_type }}  text-center">
    {!! $msg !!}
    </div>
    @endif
    <div class="register-box">
      <div class="register-logo" style="background: #d4ead7;padding: 5px;margin-bottom: 0px !important;">
        سامانه  
        <a href="/login">
        <b>بازی</b>انگاری
        </a>
        مدیریت شهر هوشمند مشهد
      </div>

      <div class="register-box-body">
        <p class="login-box-msg">ثبت نام برای وب سرویس</p>
        <form action="/register" method="post" enctype="multipart/form-data">
          @csrf
          <div class="form-group input-group has-feedback">
            <input type="number" class="form-control" onkeyup="checkMobile(event);" placeholder="09120172767" name="mobile" id="mobile">
            <span class="glyphicon glyphicon-headphones form-control-feedback"></span>
            <div class="input-group-btn" title="صحت سنجی موبایل">
              <a id="verify-btn" class="btn btn-default disabled" href="#" onclick="verifyMobile();return false;">
                <i class="glyphicon glyphicon-check" style="color: red;"></i>
                تایید شماره موبایل
              </a>
            </div>
          </div>
          <div class="form-group input-group has-feedback hidden">
            <input type="text" class="form-control" placeholder="کد صحت سنجی" id="verfiy-code">
            <span class="glyphicon glyphicon-headphones form-control-feedback"></span>
            <div class="input-group-btn" title="صحت سنجی موبایل">
              <a id="verify-btn" class="btn btn-default"  href="#" onclick="verifyCode();return false;">
                <i class="glyphicon glyphicon-check" style="color: blue;"></i>
                تایید رهگیری
              </a>
            </div>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="نام اپلیکیشن" name="name" id="name">
            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="ایمیل" name="email" id="email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="رمز عبور" name="password" id="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="تکرار رمز عبور" name="repassword" id="repassword">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="نام شرکت" name="company_name" id="company_name">
            <span class="glyphicon glyphicon-book form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="شناسه ملی شرکت" name="national_id" id="national_id">
            <span class="glyphicon glyphicon-tags form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="تاریخ تاسیس مثلا 1397/02/23" name="open_date" id="open_date">
            <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="شماره ثبت شرکت" name="register_id" id="register_id">
            <span class="glyphicon glyphicon-barcode form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="تلفن شرکت" name="tell" id="tell">
            <span class="glyphicon glyphicon-phone-alt form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="آدرس شرکت" name="address" id="address">
            <span class="glyphicon glyphicon-map-marker form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="وب سایت شرکت مثلا www.company.ir" name="website" id="website">
            <span class="glyphicon glyphicon-globe form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="آدرس آی پی که قرار است از وب سرویس استفاده کند" name="ip" id="ip">
            <span class="glyphicon glyphicon-transfer form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <div class="container-fluid" style="border: 1px solid #d4dbe3;padding: 8px;">
              <div class="row">
                <div class="col-md-7">
                  <input type="file" name="image_path" id="image_path">            
                </div>
                <div class="col-md-5">
                  <span style="margin-right: 33px;">
                  آیکون اپلیکیشن
                  </span>
                  <span class="glyphicon glyphicon-heart form-control-feedback" style="margin-top: -7px;margin-right: 6px;"></span>
                </div>
              </div>
            </div>
          </div>          
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input id="accept-check" type="checkbox"> با <a href="/laws">قوانین</a> وب سرویس موافق هستم .
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <a href="javascript:submitForm();" class="btn btn-primary btn-block btn-flat">ثبت نام</a>
            </div><!-- /.col -->
          </div>
        </form>

        <a href="/login" class="text-center">ورود اعضا</a>
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->

    <!-- jQuery 2.1.4 -->
    <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.4 -->
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="/admin/plugins/iCheck/icheck.min.js"></script>
    <script>
      var theMobile;
      var mobileState = 'notverified';
      function verifyCode() {
        var verifyCode = $("#verfiy-code").val();
        $("#verify-btn").find('i').addClass('gly-spin');
        $.get("/api/verify/" + theMobile + '/' + verifyCode, function(res) {
          // console.log(res);
          $("#verify-btn").find('i').removeClass('gly-spin');
          if(res.status==1) {
            $("#verify-btn").find('i').css('color', 'green');
            $("#verfiy-code").parent().addClass('hidden');
            $("#verify-btn").addClass('disabled');
            $("#mobile").prop('disabled', true);
            $("#verify-btn").parent().prop('title', '');
            mobileState = 'verified';
          }else {
            alert('کد وارد شده اشتباه است');
          }
        }).fail(function() {
          alert('خطا در ارتباط');
          $("#verify-btn").find('i').removeClass('gly-spin');
        });
      }
      function verifyMobile() {
        var mobile = $("#mobile").val();
        if(mobile.length==11 && mobile.indexOf('09')==0) {
          // console.log('Checking Mobile');
          $("#verify-btn").find('i').addClass('gly-spin');
          $("#verify-btn").addClass('disabled');
          $.get("/api/mobile/" + mobile, function(res) {
            if(res.status==1) {
              $("#verfiy-code").parent().removeClass('hidden');
              // $("#verify-btn").find('i').css('color', 'blue');
              // $("#verify-btn").parent().prop('title', 'بررسی کد');
              // $("#verify-btn").prop('href', 'javascript:verifyCode()');
              theMobile = mobile;
              mobileState = 'sendverify';
              $("#mobile").prop('disabled', true);
            }else {
              $("#verify-btn").removeClass('disabled');
              alert('بیش از حد مجاز تلاش کرده اید');
            }
            // $("#verify-btn").removeClass('disabled');
            $("#verify-btn").find('i').removeClass('gly-spin');
          }).fail(function() {
            alert('خطا در ارتباط');
            $("#verify-btn").find('i').removeClass('gly-spin');
            $("#verify-btn").removeClass('disabled');
          });
        }else{
          if(!$("#verify-btn").hasClass('disabled')) {
            $("#verify-btn").addClass('disabled');
          }
          alert('شماره موبایل صحیح نمی باشد');
        }
      }
      function checkMobile(evt) {
        var mobile = evt.target.value;
        if(mobile.length==11 && mobile.indexOf('09')==0) {
          $("#verify-btn").removeClass('disabled');
        }else{
          if(!$("#verify-btn").hasClass('disabled')) {
            $("#verify-btn").addClass('disabled');
          }
        }
      }
      function submitForm() {
          var empties = [], password, repassword;
          password = $("#password").val();
          repassword = $("#repassword").val();
          if(password.length < 6) {
            alert('کلمه عبور کمتر از ۶ کاراکتر نباشد');
            return false;
          }
          if(password!=repassword) {
            alert('کلمه عبور برابر تکرار آن نیست');
            return false;
          }
          if(mobileState=='notverified') {
            alert('صحت سنجی شماره همراه الزامی است');
            return false;
          }
          if(mobileState=='sendverify') {
            alert('لطفا صحت سنجی موبایل را تایید کنید');
            return false;
          }
          if(mobileState!='verified') {
            mobileState='notverified'
            alert('صحت سنجی شماره همراه الزامی است');
            return false;
          }
          $("input").each(function(id, field) {
              if((field.type=='text' || field.type=='email') && field.value=='') {
                $(field).css('border-color', 'red');
                empties.push(field);
              }
          });
          console.log(empties);
          if(empties.length == 0) {
            if($("#accept-check").prop('checked')) {
              $("#mobile").prop('disabled', false);
              $("form").submit();
            }else {
              alert('لطفا شرایط و قوانین را قبول بفرمایید');
            }
          }else {
              alert('موارد مشخص شده با رنگ قرمز را وارد کنید');
          }
      }
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>
