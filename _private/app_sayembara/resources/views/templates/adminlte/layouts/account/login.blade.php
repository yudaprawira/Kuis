@extends( config('app.be_template') . 'layouts.master_less')

@section('content')
    <div class="login-box">
      <div class="login-logo">
        <a href="/">ADMIN</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">{{ Lang::get('login.text_caption') }}</p>
        <form method="post">
          <div class="form-group has-feedback">
            <input type="text" name="username" class="form-control" placeholder="{{ Lang::get('login.input_email') }}" value="{{$username}}" required="required"/>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="{{ Lang::get('login.input_password') }}" required="required"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember_me"/> {{ Lang::get('login.text_remember') }}
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <input type="hidden" name="_token" value="{{csrf_token()}}"/>  
              <button type="submit" class="btn btn-primary btn-block btn-flat">{{ Lang::get('login.btn_signin') }}</button>
            </div><!-- /.col -->
          </div>
        </form>

        <a href="{{ BeUrl('reset-password') }}">{{ Lang::get('login.text_forgot') }}</a><br/>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop