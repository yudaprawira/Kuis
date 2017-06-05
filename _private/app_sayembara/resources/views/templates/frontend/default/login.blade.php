@extends( config('app.template') . 'layouts.master')

@section('content')
<main class="mdl-layout__content mdl-color--grey-100">
    <div class="mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-grid">
        <div class="mdl-layout-spacer"></div>
        <!-- Container for the demo -->
        <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-cell--5-col-desktop">
            <div class="mdl-card__title mdl-color--light-blue-600 mdl-color-text--white">
            <h2 class="mdl-card__title-text">Sign in with your Google account below</h2>
            </div>
            <div class="mdl-card__supporting-text mdl-color-text--grey-600">
            
                <!-- Button that handles sign-in/sign-out -->
                <div style="text-align: center;padding: 30px 0 0;" id="form-login" data-token="{{ csrf_token() }}" data-store="{{ url('login/save') }}">
                    <button class="mdl-button mdl-js-button mdl-button--raised" id="quickstart-sign-in">Sign in with Google</button>
                </div>
            </div>
        </div>
        <div class="mdl-layout-spacer"></div>
    </div>
</main>
@stop
@push('styles')
@endpush

@push('scripts')
<script src="https://www.gstatic.com/firebasejs/4.1.1/firebase.js"></script>
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
<script type="text/javascript">
    $(document).on('click', '#quickstart-sign-in', function(){
        var ypBase = new ypFireBase();
            ypBase.auth('google');
    });
    function loading(type){
        if ( type==1 )
        {
            $('#quickstart-sign-in').attr('disabled', true);
        }
        else
        {
            $('#quickstart-sign-in').attr('disabled', false);
        }
    }
</script>
@endpush