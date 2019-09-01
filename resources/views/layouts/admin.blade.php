@php
$current_url = explode('/', str_replace('http://','', url()->current()));
if(count($current_url)>1) {
    $current_url = $current_url[count($current_url)-1];
}else {
    $current_url = '';
}
$user = Auth::user();
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>یادآموز | داشبورد</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css" > -->
    <!-- Ionicons 2.0.0 -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/admin/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/admin/dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/admin/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/admin/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/admin/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/admin/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!--Persian Date Picker-->
    <link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.min.css">
    @yield('extra_css')

    <link rel="stylesheet" href="/admin/dist/fonts/fonts-fa.css">
    <link rel="stylesheet" href="/admin/dist/css/bootstrap-rtl.min.css">
    <link rel="stylesheet" href="/admin/dist/css/rtl.css">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="skin-purple-light sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="/ad" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>ی</b>وز</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>یاد</b>آموز</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="/admin/dist/img/avatar5.png" class="user-image" alt="{{ $user->name }}">
                                <span class="hidden-xs">{{ $user->fname }} {{ $user->lname }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="/admin/dist/img/avatar5.png" class="img-circle" alt="{{ $user->name }}">
                                    <p>
                                        {{ $user->name }}
                                        <!-- <small>Member since Nov. 2012</small> -->
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left1">
                                        <form method="post"  action="/changepass">
                                            @csrf
                                            <label>
                                            رمز عبور فعلی : 
                                            </label>
                                            <input type="password" class="form-control" name="password" /><br/>
                                            <label>
                                            رمز عبور جدید : 
                                            </label>
                                            <input type="password" class="form-control" name="newpassword" /><br/>
                                            <label>
                                            تکرار رمز عبور جدید : 
                                            </label>
                                            <input type="password" class="form-control" name="newpassword2" /><br/>
                                            <button class="btn btn-default btn-flat">
                                            تغییر
                                            </button>
                                            <a href="/login" class="btn btn-default btn-flat" style="float: left;">خروج</a>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-right image">
                        <img src="/admin/dist/img/avatar5.png" class="img-circle" alt="{{ $user->name }}">
                    </div>
                    <div class="pull-left info">
                        <p>{{ $user->fname }} {{ $user->lname }}</p>
                        <a href="#"><i class="fa fa-circle text-success"></i> آنلاین</a>
                    </div>
                </div>
                <!-- search form -->
                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                  <li class="header">ناوبری اصلی</li>
                  @if($user->group_id==0)
                  <li
                  @if($current_url=='course')
                   class="active"
                  @endif
                  >
                    <a href="/course">
                      <i class="fa fa-chalkboard"></i> <span>دوره</span> 
                    </a>
                  </li>
                  <li
                  @if($current_url=='user')
                   class="active"
                  @endif
                  >
                    <a href="/user">
                      <i class="fa fa-user-graduate"></i> <span>متخصصین</span> 
                    </a>
                  </li>
                  <li
                  @if($current_url=='school')
                   class="active"
                  @endif
                  >
                    <a href="/school">
                      <i class="fa fa-school"></i> <span>مدارس</span> 
                    </a>
                  </li>
                  @endif
                  <li
                  @if($current_url=='' || $current_url=='home' || $current_url=='chapter')
                   class="active"
                  @endif
                  >
                    <a href="/chapter">
                      <i class="fa fa-pencil-alt"></i> <span>فصل</span> 
                    </a>
                  </li>
                  <li
                  @if($current_url=='lesson')
                   class="active"
                  @endif
                  >
                    <a href="/lesson">
                      <i class="fa fa-file-signature"></i> <span>درس</span> 
                    </a>
                  </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        @yield('content')

        <footer class="main-footer">
            کلیه حقوق متعلق به  <strong><a target="_blank" href="http://sodafanavar.ir">شرکت سودافناور</a> &copy; 2015-2019</strong> می باشد.
        </footer>


        <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    @yield('alerts')
    
    <div class="modal fade" id="modal-default" style="display: none;">
        <div class="modal-dialog">
        <div class="modal-content" style="background: #000;color: #fff;">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">راهنما</h4>
            </div>
            <div class="modal-body" style="background: #000;color: #fff;">
                <div style="text-align: center;">
                    <img src="/admin/dist/img/help_img.png" style="height: 100px;" />
                </div>
            <p>{!! (isset($help))?$help:'' !!}</p>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">خروج</button>
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- jQuery 2.1.4 -->
    <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <!-- Bootstrap 3.3.4 -->
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="/admin/plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="/admin/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="/admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="/admin/plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="/admin/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="/admin/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="/admin/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/admin/dist/js/app.min.js"></script>
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!--Persian Date Picker-->
    <script src="https://unpkg.com/persian-date@latest/dist/persian-date.min.js"></script>
    <script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.min.js"></script>
    <script>
      $(".pdate").pDatepicker({
        format: 'YYYY/MM/DD',
        initialValue: false,
        initialValueType: 'persian',
      });
    </script>
    <!-- JQMath -->
    @yield('extra_script')
</body>

</html>
