<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"> {{ val($dataForm, 'form_title') }} </h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                
                <form method="POST" action="{{ BeUrl(config('___SC___.info.alias').'/save') }}" id="formData" <form method="POST" action="{{ BeUrl(config('___SC___.info.alias').'/save') }}" enctype="multipart/form-data">
                
                <div class="box-body">
                    <div class="text-center">
                        <img  src="{{ val($dataForm, 'image') ? asset('media/'.val($dataForm, 'image')) : asset('/global/images/no-image.png') }}" id="imagePreview"><br/><br/>
                        <a href="#" class="btn btn-sm btn-flat btn-default" id="changeImage"><i class="fa fa-pencil"></i> {{ trans('___SC___::global.change_image') }} </a>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <input type="checkbox" name="status" {{ isset($dataForm['status']) ? (val($dataForm, 'status')=='1' ? 'checked' : '') : 'checked' }} /> {{ trans('global.status_active') }}
                </div>

                <div class="form-group has-feedback">
                    <label>{{ trans('___SC___::global.___FIELD_NAME___') }}</label><span class="char_count"></span>
                    <input type="text" class="form-control" name="___FIELD_NAME___" maxlength="125" value="{{ val($dataForm, '___FIELD_NAME___') }}" />
                </div>

                <input type="hidden" name="id" value="{{ val($dataForm, 'id') }}" />
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="file" name="image" id="inputImage" />
                <button type="submit" class="btn btn-primary btn-flat">{{ val($dataForm, 'id') ? trans('global.act_edit') : trans('global.act_add') }}</button>
                <a href="{{ BeUrl(config('___SC___.info.alias').'/edit/0') }}" class="btn btn-default btn-flat btn-reset">{{ trans('global.act_back') }}</a>
                </form>
                
            </div><!-- /.box-body -->
        </div>
    </div>
</div>