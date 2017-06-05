<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }}</title>
    <meta http-equiv="refresh" content="900">
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ $pub_url }}/dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ $pub_url }}/plugins/iCheck/square/green.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ $pub_url }}/plugins/iCheck/flat/green.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ $pub_url }}/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ $pub_url }}/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ $pub_url }}/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ $pub_url }}/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ $pub_url }}/plugins/select2/select2.min.css">
    
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{ $pub_url }}/plugins/timepicker/bootstrap-timepicker.min.css">
  
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="/global/js/html5shiv.min.js"></script>
        <script src="/global/js/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="{{ asset('/global/css/datatables.bootstrap.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/global/css/fixedColumns.bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/global/css/responsive.bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/global/css/fixedHeader.bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/global/css/token-input-facebook.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/global/css/jquery.datetimepicker.min.css') }}"/>
    <!-- Main -->
    <link rel="stylesheet" href="{{ asset('/global/css/main.css') }}"/>
    
    @stack('style')
    
    <!-- FIREBASE -->
    <script src="https://www.gstatic.com/firebasejs/4.0.0/firebase.js"></script>
    <script>
    // Initialize Firebase
    var config = {
        apiKey: "AIzaSyD_G_ZNknZvIsJHnh3SZjYRQaYhYA6_nu0",
        authDomain: "kemanagitu-6f3aa.firebaseapp.com",
        databaseURL: "https://kemanagitu-6f3aa.firebaseio.com",
        projectId: "kemanagitu-6f3aa",
        storageBucket: "kemanagitu-6f3aa.appspot.com",
        messagingSenderId: "372322146671"
    };
    firebase.initializeApp(config);
    </script>
  </head>
  <body class="hold-transition skin-green sidebar-mini fixed">
    <div class="wrapper box" style="border-top: 0;">
        
      {!! $header !!}
      
      <!-- Left side column. contains the logo and sidebar -->
      {!! $Lmenu !!}

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            {{ $subtitle }} <small>{{ $desc }}</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{ BeUrl() }}"><i class="fa fa-dashboard"></i> {{ trans('global.dashboard') }}</a></li>
            <?php $no = 0; ?>
            @if( !empty($breadcrumb) )
                @foreach( $breadcrumb as $no=>$b )
                    <li class="{{ $no==(count($breadcrumb)-1) ? 'active' : '' }}"><a href="{{ $b['url'] }}">{{ $b['name'] }}</a></li>
                @endforeach
            @endif
          </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            @yield('content')            
        </section>
      </div>
      
      {!! $footer !!}

      <!-- Control Sidebar -->
      {!! $control !!}
      
      <div class="overlay" id="main-loding" style="display: none;">
          <i class="fa fa-refresh fa-spin"></i>
      </div>
    </div><!-- ./wrapper -->
    
    <!-- Notification -->
    {!! $notif !!}
    
    <!-- jQuery 2.1.4 -->
    <script src="{{ $pub_url }}/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('/global/js/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ $pub_url }}/bootstrap/js/bootstrap.min.js"></script>
    <!-- Morris.js charts -->
    <script src="{{ asset('/global/js/raphael-min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ $pub_url }}/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="{{ $pub_url }}/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="{{ $pub_url }}/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ $pub_url }}/plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('/global/js/moment.min.js') }}"></script>
    <script src="{{ $pub_url }}/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="{{ $pub_url }}/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ $pub_url }}/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="{{ $pub_url }}/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="{{ $pub_url }}/plugins/fastclick/fastclick.min.js"></script>
    <!-- Select2 -->
    <script src="{{ $pub_url }}/plugins/select2/select2.full.min.js"></script>
    <!-- Notif -->
    <script src="{{ asset('/global/js/jquery.bootstrap-growl.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ $pub_url }}/dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ $pub_url }}/dist/js/demo.js"></script>
    <!-- Datatable -->
    <script src="{{ asset('/global/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/global/js/datatables.bootstrap.js') }}"></script>
    <script src="{{ asset('/global/js/dataTables.fixedColumns.min.js') }}"></script>
    <script src="{{ asset('/global/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/global/js/responsive.bootstrap.min.js') }}"></script>
    <script src="{{ asset('/global/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('/global/js/jquery.tokeninput.js') }}"></script>
    <!-- Main -->
    <script src="{{ asset('/global/js/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('/global/js/number.js') }}"></script>
    <script src="{{ asset('/global/js/main.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ $pub_url }}/plugins/iCheck/icheck.min.js"></script>
    <script src="{{ asset('/global/js/ypfirebase.js') }}"></script>
    <script src="{{ asset('/global/js/ypfirebaseresto.js') }}"></script>
    
    <!-- CUSTOM JS -->
    <script>
        <?= packerJs("

        var ypUser = new ypFireBase('resto/".val($active_state, 'url')."/user/".(session::get('ses_level_url'))."/');

        //PELAYAN NOT ONLINE
        ypUser.offline(".session::get('ses_userid').").update({'online':false});

        $(function() {    
            if ( $('#list-table').length>0 || $('.form-ajax').length>0 )
            {
                if ( $('#list-table').length>0 )
                {
                    var objTbl = $('#list-table');
                    var objCol= [];
                    objTbl.find('thead th').each(function(i, v){
                        objCol.push({ 
                            'data': $(v).data('column'), 
                            'orderable': $(v).data('sort') ? true : false, 
                            'searchable': $(v).data('search') ? true : false, 
                        });
                    });

                    var oTable = objTbl.DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: objTbl.data('responsive'),
                        order : [[0, 'desc']],
                        ajax: {
                            url: objTbl.data('url'),
                            method: 'POST',
                            data: {
                                '_token': objTbl.data('token'),
                            }
                        },
                        columns: objCol,
                        'language': {
                            'lengthMenu': '".trans('global.table_menu')."',
                            'zeroRecords': '".trans('global.table_nothing')."',
                            'info': '".trans('global.table_info')."',
                            'infoEmpty': '".trans('global.table_empty')."',
                            'infoFiltered': '".trans('global.table_filter')."',
                            'search': '".trans('global.table_search')."'
                        },
                        'drawCallback': function(){
                            //move modal confirmation
                            var listModel = '';
                            $('.areaModalConfirm').each(function(){
                                listModel+= $(this).html();
                            });
                            $('.areaModalConfirm').remove();
                            objTbl.after(listModel);
                        }
                    });

                    //FIXED HEADER
                    if ( objTbl.data('fixedheader') )
                    {
                       var fxHeader = new $.fn.dataTable.FixedHeader( oTable );
                           fxHeader.headerOffset($('header').outerHeight()) 
                    }

                }
                
                //SUBMIT
                $(document).on('submit', '#formData, #uploader_formData', function(){
                    
                    var inputImage = $('#uploader_inputImage').length>0 ? $('#uploader_inputImage') : null;
                    var inputImage = inputImage ? inputImage : ($('#inputImage').length>0 ? $('#inputImage') : null);
                    var isUploader = $(this).attr('id')=='uploader_formData' ? true : false;

                    if ( inputImage && inputImage.val() ) 
                    {
                        var form = $(this);
                        var formData = new FormData();
                        formData.append('file', inputImage[0].files[0]);
                        formData.append('_post', form.serialize());
                        formData.append('_token', '".csrf_token()."');

                        var overlay = form.closest('.modal').find('.overlay');
                        
                        $.ajax({
                            type		: 'POST',
                            url			: form.attr('action'),
                            data        : formData,
                            cache       : false,
                            contentType : false,
                            processData : false,
                            beforeSend	: function(xhr) { loading(1); overlay.show(); },
                            xhr: function() {  // custom xhr
                                myXhr = $.ajaxSettings.xhr();
                                if(myXhr.upload){ // if upload property exists
                                    myXhr.upload.addEventListener('progress', function(e){
                                        var percent = (e.loaded / e.total) * 100;
                                        console.log(percent);
                                    }, false); // progressbar
                                }
                                return myXhr;
                            },
                            error: errorHandler = function() {
                                alert('Something went wrong!');
                            },
                            success		: function(dt){
                            
                            if ( dt.status && $('#list-table').length>0 ) oTable.ajax.reload();

                            if ( dt.status && form.closest('.modal').length>0 ) form.closest('.modal').attr('isReload', true);
                            
                            if ( dt.form ) {form.closest('.box').replaceWith(atob(dt.form));initPlugin();}
                            
                            if ( dt.message ) initNotif(atob(dt.message));

                            if ( dt.rdr ) window.location.href = dt.rdr;

                            if ( isUploader && dt.result.url && dt.result.path)
                            {
                                selectedImage(dt.result.url, dt.result.path, dt.result.copyright, dt.result.description);
                                //reload list
                                $('#imageSelectorLibrary .form-filter').submit();
                            }
                
                            },
                        }).done(function(){ overlay.hide(); loading(0);  }); 
                    }
                    else
                    {    
                        var form = $(this);
                        
                        $.ajax({
                            type		: 'POST',
                            url			: form.attr('action'),
                            data        : form.serialize(),
                            beforeSend	: function(xhr) { loading(1) },
                            success		: function(dt){
                            
                            if ( dt.status && $('#list-table').length>0) oTable.ajax.reload();
                            
                            if ( dt.form ) {form.closest('.box').replaceWith(atob(dt.form));initPlugin();}
                            
                            if ( dt.message ) initNotif(atob(dt.message));

                            if ( dt.rdr ) window.location.href = dt.rdr;

                            if ( dt.status && form.closest('.modal').length>0 ) form.closest('.modal').attr('isReload', true);
                
                            },
                        }).done(function(){ loading(0) }); 
                    }
                    
                    return false;
                });
                
                //EDIT & RESET & DELETE
                $(document).on('click', '.btn-edit, .btn-reset, .btn-delete', function(){
                    
                    var ButtonElm = $(this);
                    var isDelete = ButtonElm.hasClass('btn-delete');
                    var url = ButtonElm.attr('href');
                    var modal = ButtonElm.closest('.modalConfirmation');
                    var isDataTable = ButtonElm.closest('table');

                    if ( $('#formData').length==0 && $('#page-imglib').length==0 && !isDelete) 
                    {
                        window.location.href = url; 

                        return false;
                    }
                    
                    $.ajax({
                		type		: 'GET',
                		url			: url,
                		beforeSend	: function(xhr) { if ( isDelete ) { modal.find('.delete-loding').show(); } else { loading(1); } },
                		success		: function(dt){
                		  if ( isDelete )
                          {
                              if ( $('#list-table').length>0) oTable.ajax.reload();

                              //close modal
                              modal.find('.close').click();  
                              //reset
                              $(ButtonElm).closest('form').find('.btn-reset').click();

                              if ( dt.message ) initNotif(atob(dt.message));

                              if ( dt.status ) modal.attr('isReload', true);
                          }
                          else
                          {
                              if(isDataTable.attr('id')=='list-table' || ButtonElm.hasClass('btn-reset'))
                              {
                                  $('#formData').closest('.box').replaceWith(dt);
                              }
                              else
                                  $('#uploader_formData').closest('.box').replaceWith(dt);
                              
                              initPlugin();
                          } 
                        },
                	}).done(function(){ if ( isDelete ) { modal.find('.delete-loding').hide(); } else { loading(0); } });
                    
                    return false;
                });    
            }

            //UPLOAD
            if ( $('#modalImageSelector').length>0 )
            {
                $(document).on('click', '#changeImage', function(){
                    $('#modalImageSelector').modal('show');
                    $('#inputImage').attr('type', 'hidden');
                    $('#modalImageSelector').attr('target-url', '#imagePreview');
                    $('#modalImageSelector').attr('target-path', '#inputImage');
                    $('#modalImageSelector').attr('target-copyright', '#inputCopyright');
                    return false;
                });

                changeImage('#uploader_changeImage', '#uploader_imagePreview', '#uploader_inputImage');
            }
            else
            {
                changeImage('#changeImage', '#imagePreview', '#inputImage');
            }

            $('[name=username]').focus();
            
            $(document).on('submit', 'form', function(){
                $('#main-loding').show()
            });
                
               
        });

        function changeImage(btn, trgt, inpt)
        {
            if ( $(btn).length>0 && $(trgt).length>0 && $(inpt).length>0 )
            {
                $(document).on('click', btn, function(){
                    $(inpt).trigger('click');
                    return false;
                });
                $(document).on('change', inpt, function(){
                    readURL(this, trgt);
                });
            }
        }

        function selectedImage(url, path, copyright, description)
        {
            if ( $($('#modalImageSelector').attr('target-url')).length>0 )
            {
                $($('#modalImageSelector').attr('target-url')).attr('src', url);
            }
            if ( $($('#modalImageSelector').attr('target-path')).length>0 )
            {
                $($('#modalImageSelector').attr('target-path')).val(path);
            }
            if ( $($('#modalImageSelector').attr('target-copyright')).length>0 )
            {
                $($('#modalImageSelector').attr('target-copyright')).val(copyright);
            }
            if ( $($('#modalImageSelector').attr('target-description')).length>0 )
            {
                $($('#modalImageSelector').attr('target-description')).val(description);
            }
            $('#modalImageSelector').modal('hide');
        }

        function readURL(input, elm) 
        {
            if (input.files && input.files[0]) 
            {
                var reader = new FileReader();            
                reader.onload = function (e) {
                    
                    $(elm).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        ")?>
    </script>
    <!-- EOF CUSTOM JS -->
    
    @if (function_exists('imageSelector'))
        {!! imageSelector() !!}
    @endif
    
    @stack('scripts')
  </body>
</html>