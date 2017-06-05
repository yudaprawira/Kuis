@extends( config('app.be_template') . 'layouts.master_less')

@section('content')
    <div class="login-box">
      <div class="login-logo">
        <a href="#">{{ Lang::get('login.title_create') }}</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">{{ Lang::get('login.text_create') }}</p>
        <form method="post">
          <div class="form-group has-feedback">
            <span class="form-control disabled" disabled="disabled">{{ $user->email }}</span>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="new_password" class="form-control" placeholder="{{ Lang::get('passwords.input_newpass') }}" value="" required="required"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ Lang::get('passwords.input_repass') }}" value="" required="required"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
                  <a href="{{ config('app.url') }}/login" class="btn btn-default btn-flat">{{ Lang::get('passwords.btn_chancel') }}</a>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <input type="hidden" name="code" value="{{$code}}"/>  
              <input type="hidden" name="_token" value="{{csrf_token()}}"/>  
              <button type="submit" class="btn btn-primary btn-block btn-flat">{{ Lang::get('passwords.btn_create') }}</button>
            </div><!-- /.col -->
          </div>
        </form>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop