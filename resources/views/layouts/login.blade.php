<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>رسیدگی و نظارت</title>
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
  </head>
  <body class="login-page text-center" style="background-image: url(/admin/dist/img/mashhad.png);background-position: center;background-repeat: no-repeat;background-size: cover;">
    @if($msg!='')
    <div class="alert alert-{{ $msg_type }}">
    {{ $msg }}
    </div>
    @endif
    <div class="login-box">
      <div class="login-logo" style="background: #d4ead7;padding: 5px;margin-bottom: 0px !important;">
        <a href="/"><b>رسیدگی</b>نظارت</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">برای استفاده از پنل سامانه رسیدگی و نظارت وارد شوید</p>
        <form action="" method="post">
            @csrf
          <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="ایمیل" name="email" id="email" >
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="پسورد" name="password" >
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8" style="text-align: left;">
              <!-- <a class="btn btn-info" id="forget-pass-btn" href="javascript:forgetPass()">
              رمز را فراموش کردم
              </a> -->
              <!-- <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> مرا به خاطر بسپار
                </label>
              </div> -->
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">ورود</button>
            </div><!-- /.col -->
          </div>
        </form>

        <!-- <a href="#">I forgot my password</a><br> -->
        <!-- <a href="/register" class="text-center">ثبت نام وب سرویس</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="/resident_login" class="text-center">ورود شهروند</a> -->
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.4 -->
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="/admin/plugins/iCheck/icheck.min.js"></script>
    <script>
      function forgetPass(){
        if($("#email").val().trim().length<=4) {
          alert('ایمیل خود را به درستی وارد کنید');
          return false;
        }
        $("#forget-pass-btn").prop('disabled', true);
        $.post('/api/changepass', {email: $("#email").val().trim()}, function(result) {
          $("#forget-pass-btn").prop('disabled', false);
          console.log(result);
          alert('درصورتی شماره موبایل در اطلاعات شما باشد ، رمز عبور جدید برای شما ارسال شده');
        }).fail(function() {
          $("#forget-pass-btn").prop('disabled', false);
          alert('خطا در برقراری ارتباط');
        });
        return false;
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