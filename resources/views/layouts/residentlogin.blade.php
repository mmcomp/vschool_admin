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
  </head>
  <body class="login-page text-center" style="background-image: url(/admin/dist/img/koohsangi.jpg);background-position: center;background-repeat: no-repeat;background-size: cover;">
    @if($msg!='')
    <div class="alert alert-{{ $msg_type }}">
    {{ $msg }}
    </div>
    @endif
    <div class="login-box">
      <div class="login-logo" style="background: #d4ead7;padding: 5px;margin-bottom: 0px !important;">
        <a href="/"><b>بازی</b>انگاری</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">برای اطلاع از وضعیت شهروندی خود وارد شوید</p>
        <form action="" method="post">
            @csrf
          <div class="form-group has-feedback">
            <input type="text" id="mobile" class="form-control" placeholder="موبایل" name="email" onkeyup="checkMobile(event);" placeholder="شماره همراه مثلا 09120172769">
            <span id="verify-btn" class="glyphicon glyphicon-phone form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback" style="display:none;" id="verify-div">
            <a href="javascript:sendVerify()" >دریافت کد تایید</a>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="کد تایید" name="password" >
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">ورود</button>
            </div><!-- /.col -->
          </div>
        </form>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.4 -->
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="/admin/plugins/iCheck/icheck.min.js"></script>
    <script>
      var theMobile = '', verifying = false;
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
      function checkMobile(evt) {
        var mobile = evt.target.value;
        if(mobile.length==11 && mobile.indexOf('09')==0) {
          $(evt.target).css('color', 'green');
          $("#verify-div").show();
          theMobile = mobile;
        }else{
          $(evt.target).css('color', 'red');
          $("#verify-div").hide();
        }
      }
      function sendVerify() {
        if(theMobile!='' && !verifying) {
          $("#verify-btn").addClass('gly-spin');
          $("#verify-div").find('a').css('color', 'red');
          verifying = true;
          $.get("/api/rmobile/" + theMobile, function(res) {
            if(res.status==1) {
              // $("#mobile").prop('disabled', true);
            }else {
              alert('بیش از حد مجاز تلاش کرده اید');
            }
            // $("#verify-btn").removeClass('disabled');
            $("#verify-btn").removeClass('gly-spin');
            $("#verify-div").find('a').css('color', '#3c8dbc');
            verifying = false;
          }).fail(function() {
            alert('خطا در ارتباط');
            $("#verify-btn").find('i').removeClass('gly-spin');
            $("#verify-div").find('a').css('color', '#3c8dbc');
            verifying = false;
          });
        }else if(verifying) {
          alert('لطفا منتظر بمانید');
        }
      }
    </script>
  </body>
</html>