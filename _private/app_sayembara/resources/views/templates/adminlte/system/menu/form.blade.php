<div class="box">
    <div class="box-header">
      <h3 class="box-title">{{ $title }}</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
        <form role="form" action="{{ BeUrl('system-menu/save') }}" method="POST" id="formDataMenu">
            <div class="form-group has-feedback" style="display: none;" id="data-parent">
              <label>{{ trans('system/menu.parent') }}</label>
              <span class="form-control disabled" disabled="disabled"></span>
              <input type="hidden" class="form-control" name="parent_id" value="" />
            </div>
            <div class="form-group has-feedback">
              <label>{{ trans('system/menu.name') }}</label><span class="char_count"></span>
              <input type="text" class="form-control" name="name" maxlength="35" value="{{ isset($name) ? $name : '' }}" />
            </div>
            <div class="form-group has-feedback">
              <label>{{ trans('system/menu.url') }}</label><span class="char_count"></span>
              <input type="text" class="form-control" name="url" maxlength="35" value="{{ isset($url) ? $url : '' }}" />
            </div>
            <div class="form-group has-feedback">
              <label>{{ trans('system/menu.icon') }}</label><span class="char_count"></span>
              <input type="text" class="form-control" name="icon" maxlength="15" value="{{ isset($icon) ? $icon : '' }}" />
            </div>
            <div class="form-group has-feedback">
              <label>{{ trans('system/menu.desc') }}</label><span class="char_count"></span>
              <textarea class="form-control" name="description" maxlength="75">{{ isset($description) ? $description : '' }}</textarea>
            </div>
            
            @if ( !isset($id) )  
              <input type="checkbox" id="create_module" name="module[check]" value="1" checked="true"/> {{ trans('system/menu.create_module') }}
              <br/>
              <div id="area-module">
                <div class="form-group has-feedback">
                  <label>{{ trans('system/module.type_module') }}</label>
                  <select name="module[type_module]" class="form-control">
                    <option value="full_page">{{ trans('system/module.full_module') }}</option>
                    <option value="full_page_image">{{ trans('system/module.full_module_image') }}</option>
                    <option value="part_page">{{ trans('system/module.part_module') }}</option>
                    <option value="part_page_image">{{ trans('system/module.part_module_image') }}</option>
                  </select>
                </div>
                <div class="form-group has-feedback">
                  <label>{{ trans('system/menu.field_name') }}</label><span class="char_count"></span>
                  <input type="text" class="form-control" name="module[field]" maxlength="15" placeholder="title" />
                </div>
                <div class="form-group has-feedback">
                  <label>{{ trans('system/menu.field_value') }}</label><span class="char_count"></span>
                  <input type="text" class="form-control" name="module[value]" maxlength="15" placeholder="Judul" />
                </div>
              </div>
              <br/>
            @endif
            
            <input type="hidden" name="id" value="{{ isset($id) ? $id : '' }}" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <button type="submit" class="btn btn-primary btn-flat">{{ isset($id) ? trans('global.act_edit') : trans('global.act_add') }}</button>
            <a href="{{ BeUrl('system-menu/edit/0') }}" class="btn btn-default btn-flat menu-btn-reset">{{ trans('global.act_cancel') }}</a>
        </form>
    </div>
</div>
@push('style')
<style>
#area-module {
    margin: 10px 0;
    background: #eaeaea;
    border: 1px solid #ddd;
    padding: 5px;
}
</style>
@endpush
@push('scripts')
<script>
$(document).ready(function(){

  $("#create_module").on('ifChanged', function(e){
    
      var isChecked = e.target.checked;
      if (isChecked)
        $('#area-module').slideDown();
      else
        $('#area-module').slideUp();
    });
})
</script>
@endpush