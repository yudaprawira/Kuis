<div class="box">
    <div class="box-header">
      <h3 class="box-title">{{ $title }}</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
        <form role="form" action="{{ BeUrl('system-level/save') }}" method="POST" id="formData">
            <div class="form-group has-feedback">
                <input type="checkbox" name="status" {{ isset($status) ? ($status=='1' ? 'checked' : '') : 'checked' }} /> {{ trans('global.status_active') }}
            </div>
            <div class="form-group has-feedback">
              <label>{{ trans('system/level.name') }}</label><span class="char_count"></span>
              <input type="text" class="form-control" name="name" maxlength="20" value="{{ isset($name) ? $name : '' }}" />
            </div>
            <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <button type="submit" class="btn btn-primary btn-flat">{{ isset($id) ? trans('global.act_edit') : trans('global.act_add') }}</button>
            <a href="{{ BeUrl('system-level/edit/0') }}" class="btn btn-default btn-flat btn-reset">{{ trans('global.act_cancel') }}</a>
        </form>
    </div>
</div>