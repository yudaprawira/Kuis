@extends( config('app.template') . 'layouts.master')

@section('content')
<meta name="google-signin-client_id" content="762869468686-nm51ufclv6n9sn63ltuc957qlkmlo56s.apps.googleusercontent.com">
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
                    <div class="g-signin2" data-onsuccess="onSignIn"></div>
                </div>
            </div>
        </div>
        <div class="mdl-layout-spacer"></div>
    </div>
</main>
@stop
@push('styles')
<style>
	.abcRioButtonLightBlue{
		margin: 0 auto;
	}
</style>
@endpush

@push('scripts')
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script type="text/javascript">
<<<<<<< HEAD
    
    @if ( val($_GET, 'out') )
    signOut();
    @endif

=======
>>>>>>> origin/master
    function onSignIn(googleUser) 
	{
		var profile = googleUser.getBasicProfile();
		var rowData = {
            'id' : profile.getId(),
            'nama' : profile.getName(),
            'image' : profile.getImageUrl(),
            'email' : profile.getEmail(),
            'type' : 'google',
            '_token': $('#form-login').data('token')
        };

        $.ajax({
            type		: 'POST',
            url			: $('#form-login').data('store'),
            data        : rowData,
            beforeSend	: function(xhr) {  },
            success		: function(dt){                
                swal(dt.data_user,
                function(){
                    window.location.href="/";
                });
				if ( dt.data_user.type!='success' )
				{
					signOut();
				}
            },
        }).done(function(){   });
    }
	function signOut() 
	{
		var auth2 = gapi.auth2.getAuthInstance();
		auth2.signOut().then(function () {
		  console.log('User signed out.');
		});
	}
</script>
@endpush
