<li class="dd-item dd3-item" data-id="{{ $id }}" id="listnested-{{ $id }}">
    <div class="dd-handle dd3-handle fa fa-{{ $icon }}"></div>
    <div class="dd3-content clearfix"><span {!! $status=='1' ? '' : 'class="text-danger"' !!}>{{ $name }}</span>
        <div class="pull-right" style="margin-top: -2px;">
            <button type="button" class="btn btn-info btn-flat btn-xs" title="{{ trans('global.delete') }}" data-toggle="modal" data-target="#modalAccess-{{$id}}" data-backdrop="static"> <i class="fa fa-users"></i> </button>
            <a href="#" class="btn btn-xs btn-primary btn-flat menu-btn-add" title="{{ trans('global.add') }}" data-id="{{$id}}" data-name="{{ $name }}"><i class="glyphicon glyphicon-plus"></i></a>
            <a href="{{ BeUrl('system-menu/edit/'.$id) }}" title="{{ trans('global.edit') }}" class="btn btn-xs btn-success btn-flat menu-btn-edit"><i class="glyphicon glyphicon-pencil"></i></a>
            <button type="button" class="btn btn-danger btn-flat btn-xs" title="{{ trans('global.delete') }}" data-toggle="modal" data-target="#modalConfirmation-{{$id}}" data-backdrop="static"> <i class="glyphicon glyphicon-trash"></i> </button>
        </div>
    </div>
    {!! $sub !!}
    
        <!-- Modal Level Access -->
        <div class="modal fade modalAccess" id="modalAccess-{{$id}}" tabindex="-1" role="dialog" aria-labelledby="modalAccess-{{$id}}-Label" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content box">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalAccess-{{$id}}-Label">{{ trans('global.confirm_access') }}</h4>
              </div>
              <form method="POST" action="{{ BeUrl('system-menu/access/'.$id) }}" class="form-access">
                  <div class="modal-body">
                    {!! trans('global.access_text', ['menu'=>$name]) !!}
                    @if( !empty($level) ) 
                    <ul style="margin: 0; padding: 0; list-style: none;">
                        @foreach( $level as $k=>$v )
                            <li> 
                                <div class="checkbox icheck">
                                    <label>
                                      <input type="checkbox" name="access[{{ $k }}]" {{ isset($access[$k]) ? 'checked="checked"' : '' }} /> {{ $v }}
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @endif
                  </div>
                  <div class="modal-footer">
                    
                    <div class="pull-left">
                        <input type="checkbox" class="checkAll" /> {{ trans('global.all') }}
                    </div>
                    
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">{{ trans('global.act_close') }}</button>
                    <button type="submit" class="btn btn-primary btn-flat btn-saveAccess">{{ trans('global.save') }}</button>
                  </div>
                  <div class="overlay access-loding" style="display: none;">
                      <i class="fa fa-refresh fa-spin"></i>
                  </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Modal DELETE -->
        <div class="modal fade modalConfirmation" id="modalConfirmation-{{$id}}" tabindex="-1" role="dialog" aria-labelledby="modalConfirmation-{{$id}}-Label" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content box">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalConfirmation-{{$id}}-Label">{{ trans('global.confirm_del') }}</h4>
              </div>
              <div class="modal-body">
                {!! trans('global.confirm_text', ['name'=>$name]) !!}
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">{{ trans('global.act_close') }}</button>
                <a href="{{ BeUrl('system-menu/delete/'.$id) }}" data-id="#listnested-{{ $id }}" class="btn btn-primary btn-flat menu-btn-delete">{{ trans('global.delete') }}</a>
              </div>
              
              <div class="overlay delete-loding" style="display: none;">
                  <i class="fa fa-refresh fa-spin"></i>
              </div>
            </div>
          </div>
        </div>

</li>