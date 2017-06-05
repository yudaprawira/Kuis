<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }} | {{ config('app.title') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ $pub_url }}/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/global/font-awesome-4.6.1/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('/global/ionicons-master/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ $pub_url }}/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ $pub_url }}/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Main -->
    <link rel="stylesheet" href="{{ asset('/global/css/main.css') }}"/>
  </head>
  <body class="hold-transition login-page">
    <div class="login-box box">
      @yield('content')
      <div class="overlay" id="main-loding" style="display: none;">
          <i class="fa fa-refresh fa-spin"></i>
      </div>
    </div><!-- /.login-box -->
    
    <!-- Notification -->
    {!! $notif !!}

    <!-- jQuery 2.1.4 -->
    <script src="{{ $pub_url }}/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ $pub_url }}/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="{{ $pub_url }}/plugins/iCheck/icheck.min.js"></script>
    <!-- Notif -->
    <script src="{{ asset('/global/js/jquery.bootstrap-growl.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ $pub_url }}/plugins/select2/select2.full.min.js"></script>
    <!-- Main -->
    <script src="{{ asset('/global/js/main.js') }}"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
        $('[name=username]').focus();
        
        $(document).on('submit', 'form', function(){
            $('#main-loding').show()
        });
      });
    </script>
  </body>
</html>
