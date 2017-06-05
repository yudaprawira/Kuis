@extends( config('app.be_template') . 'layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Module</h3>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <form method="POST">
                    <table class="table table-striped table-bordered" id="list-module">
                      <thead>
                      <tr>
                        <th class="text-center" style="width: 10px;"><input type="checkbox" id="checkAll"></th>
                        <th class="col-md-3">{{ trans('system/module.name') }}</th>
                        <th>{{ trans('system/module.desc') }}</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach( $rowMods as $k=>$m )
                        <tr>
                          <td class="text-center"><input type="checkbox" class="check-module" name="modules[]" value="{{ $k }}" /></td>
                          <td>
                            {!! text(val($m, 'name'), val($m, 'active'), 'module-enable', 'module-disabled') !!}
                            <br/>
                            @if ( val($m, 'active') )
                              <a href="#" class="link-actv">{{ trans('system/module.inactivate') }}</a>
                            @else
                              <a href="#" class="link-inactv">{{ trans('system/module.activate') }}</a>
                            @endif
                          </td>
                          <td>
                            {{ val($m, 'description', '-') }}
                            <br/>
                            {{ trans('system/module.version') }} {{ val($m, 'info.version') }} |
                            {{ trans('system/module.author') }} {!! linkable(val($m, 'author.name'), val($m, 'author.site'), '_blank') !!} 
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2">
                            <div class="row">
                              <div class="col-md-8">
                                <select name="act" class="form-control btn-flat">
                                  <option>{{ trans('system/module.actions') }}</option>
                                  <option value="true">{{ trans('system/module.activate') }}</option>
                                  <option value="false">{{ trans('system/module.inactivate') }}</option>
                                </select>  
                              </div>
                              <div class="col-md-4" style="padding-left: 0;">
                                <input type="hidden" name="_token" value="{{csrf_token()}}"/> 
                                <button type="submit" class="btn btn-flat btn-default">{{ trans('global.apply') }}</button>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </form>
                </div><!-- /.box-body -->
              </div>
        </div>
    </div>
@stop
@push('style')
<style>
  .module-enable {
    font-weight: bold;
  }
  .module-disabled {
    color: #949494;
  }
</style>
@endpush
@push('scripts')
<script>
  $(document).ready(function(){

    $("#checkAll").on('ifChanged', function(e){
      var isChecked = e.target.checked;
      if (isChecked)
        $('.check-module').iCheck('check');
      else
        $('.check-module').iCheck('uncheck');
    });

    $(document).on("click", ".link-actv, .link-inactv", function(){
      $(this).closest('tr').find('[type=checkbox]').iCheck('check');
      
      if ( $(this).attr('class')=='link-actv' )
        $('[name=act]').val("false");
      else
        $('[name=act]').val("true");

        $(this).closest('form').submit();
        
      return false;
    });

  });
</script>
@endpush