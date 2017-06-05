
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{config('app.title')}}</title>
        <meta charset="utf-8">
        <meta name="robots" content="index, follow">
        <meta name="author" content="Yuda Prawira">
        <meta name="description" content="Sayembara IT Commerce.">
        <meta name="keywords" content="sayembara, quiz">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <style>
            @-webkit-viewport { width: device-width; }
            @-moz-viewport { width: device-width; }
            @-ms-viewport { width: device-width; }
            @-o-viewport { width: device-width; }
            @viewport { width: device-width; }
            .sitewrapper{
                max-width: 500px;
                margin: 100px auto 0;
            }
            .mdl-layout__drawer-button .material-icons{
                color: #FFF;
            }
        </style>
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet" type="text/css">
        <link href="{{ asset('/global/css/sweetalert.css') }}" rel="stylesheet"/>
        <link href="{{ $pub_url }}/css/main_.css" rel="stylesheet"/>
        @stack('styles')
                
        <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.orange-indigo.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>
        <script>
            function leavePage() {
                if ( $('.form-question').length>0 && !$('.form-question').attr('done') )
                {
                    return "Are you sure you want to leave the current page?";
                }
            }
        </script>
    </head>
    <body {!! session::has('ses_feuserid') ? 'onbeforeunload="return leavePage()"': '' !!}>




        <!-- Always shows a header, even in smaller screens. -->
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
            <header class="mdl-layout__header mdl-color-text--white mdl-color--light-blue-700">
                <div class="mdl-layout__header-row">
                <!-- Title -->
                <span class="mdl-layout-title">{{ config('app.title') }}</span>
                <!-- Add spacer, to align navigation to the right -->
                <div class="mdl-layout-spacer"></div>
                <!-- Navigation. We hide it in small screens. -->
                <nav class="mdl-navigation mdl-layout--large-screen-only">
                    @if ( session::has('ses_feuserid') )
                        <a href="{{url('logout')}}" class="mdl-navigation__link mdl-color-text--white" title="Login as {{session::get('ses_feusername')}}"><img src="{{session::get('ses_feuserfoto')}}" style="width: 64px;height: 64px;float: left;margin-top: 0px;margin-right: 5px;"/>LOGOUT</a>
                    @endif
                </nav>
                </div>
            </header>
            <div class="mdl-layout__drawer">
                <span class="mdl-layout-title">{{ config('app.title') }}</span>
                <nav class="mdl-navigation">
                @if ( session::has('ses_feuserid') )
                <a class="mdl-navigation__link" href="{{url('')}}">Start Exam</a>
                <a class="mdl-navigation__link" href="{{url('hasil')}}">My Score</a>
                <a class="mdl-navigation__link" href="{{url('ranking')}}">Ranking</a>
                <a class="mdl-navigation__link" href="{{url('logout')}}">Logout</a>
                @endif
                </nav>
            </div>
            @yield('content') 
        </div>


       
        <script src="{{ asset('/global/js/jquery-2.2.4.min.js') }}"></script>
        <script src="{{ asset('/global/js/ypfirebase.js') }}"></script>
        <script src="{{ asset('/global/js/sweetalert.min.js') }}"></script>
        
         @stack('scripts')
    </body>
</html>