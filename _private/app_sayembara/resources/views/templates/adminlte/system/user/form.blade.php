<div class="box">
    <div class="box-header">
      <h3 class="box-title">{{ $title }}</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
        <form role="form" action="{{ BeUrl('system-user/save') }}" method="POST" id="formData">
            <div class="form-group has-feedback">
                <input type="checkbox" name="status" {{ isset($status) ? ($status=='1' ? 'checked' : '') : 'checked' }} /> {{ trans('global.status_active') }}
            </div>
            <div class="form-group has-feedback">
              <label>{{ trans('system/level.user') }}</label>
              <select name="level_id" class="form-control select2">
                @if ( $level )
                    @foreach( $level as $k=>$v )
                        <option value="{{ $k }}" {{ isset($level_id) && $level_id==$k  ? 'selected="selected"' : '' }} > {{ $v }} </option>
                    @endforeach
                @endif
              </select>
            </div>
            <div class="form-group has-feedback">
              <label>{{ trans('system/user.name') }}</label><span class="char_count"></span>
              <input type="text" class="form-control" name="username" maxlength="50" value="{{ isset($username) ? $username : '' }}" />
            </div>
            <div class="form-group has-feedback">
              <label>{{ trans('system/user.email') }}</label><span class="char_count"></span>
              <input type="text" class="form-control" name="email" maxlength="75" value="{{ isset($email) ? $email : '' }}" />
            </div>
            <div class="form-group has-feedback">
              <label>{{ trans('login.input_password') }}</label><span class="char_count"></span>
              <input type="password" class="form-control" name="password" maxlength="125" value="" {{ isset($id) ? '' : 'required="required"' }}/>
            </div>
            <div class="form-group has-feedback">
              <label>{{ trans('login.repeatpassword') }}</label><span class="char_count"></span>
              <input type="password" class="form-control" name="password_confirmation" maxlength="125" value="" {{ isset($id) ? '' : 'required="required"' }} />
            </div>
            <div>
                <small>*{{ trans('system/user.change_pass') }}</small>
            </div>
            <br />
            <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <button type="submit" class="btn btn-primary btn-flat">{{ isset($id) ? trans('global.act_edit') : trans('global.act_add') }}</button>
            <a href="{{ BeUrl('system-user/edit/0') }}" class="btn btn-default btn-flat btn-reset">{{ trans('global.act_cancel') }}</a>
        </form>
    </div>
</div>