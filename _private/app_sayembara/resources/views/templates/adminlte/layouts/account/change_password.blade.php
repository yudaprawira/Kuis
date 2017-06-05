@extends( config('app.be_template') . 'layouts.master_less')

@section('content')
    <div class="login-box">
      <div class="login-logo">
        <a href="#">{{ Lang::get('passwords.text_caption') }}</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <form method="post">
          <div class="form-group has-feedback">
            <input type="password" name="old_password" class="form-control" placeholder="{{ Lang::get('passwords.input_oldpass') }}" value="" required="required"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="new_password" class="form-control" placeholder="{{ Lang::get('passwords.input_newpass') }}" required="required"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ Lang::get('passwords.input_repass') }}" required="required"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <a href="{{ BeUrl() }}" class="btn btn-default btn-flat">{{ Lang::get('passwords.btn_chancel') }}</a>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <input type="hidden" name="_token" value="{{csrf_token()}}"/>  
              <button type="submit" class="btn btn-primary btn-block btn-flat">{{ Lang::get('passwords.btn_change') }}</button>
            </div><!-- /.col -->
          </div>
        </form>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop