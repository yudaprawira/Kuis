@extends( config('app.be_template') . 'layouts.master_less')

@section('content')
    <div class="login-box">
      <div class="login-logo">
        <a href="#">{{ Lang::get('login.title_reset') }}</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">{{ Lang::get('login.text_reset') }}</p>
        <form method="post">
          <div class="form-group has-feedback">
            <input type="text" name="username" class="form-control" placeholder="{{ Lang::get('login.input_email') }}" value="" required="required"/>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
                  <a href="{{ BeUrl('login') }}" class="btn btn-default btn-flat">{{ Lang::get('login.btn_signin') }}</a>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <input type="hidden" name="_token" value="{{csrf_token()}}"/>  
              <button type="submit" class="btn btn-primary btn-block btn-flat">{{ Lang::get('login.btn_reset') }}</button>
            </div><!-- /.col -->
          </div>
        </form>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop