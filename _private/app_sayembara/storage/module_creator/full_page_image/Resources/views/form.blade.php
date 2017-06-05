@extends( config('app.be_template') . 'layouts.master')

@section('content')
<form method="POST" action="{{ BeUrl(config('___SC___.info.alias').'/save') }}" enctype="multipart/form-data">

    <div class="row">
        <div class="col-md-4">
            <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"> {{ trans('___SC___::global.image') }} </h3>
                    <a href="#" class="btn btn-sm btn-flat btn-default pull-right" id="changeImage"><i class="fa fa-pencil"></i> {{ trans('___SC___::global.change_image') }} </a>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="text-center">
                        <img  src="{{ val($dataForm, 'image') ? asset('media/'.val($dataForm, 'image')) : asset('/global/images/no-image.png') }}" id="imagePreview">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"> {{ val($dataForm, 'form_title') }} </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  
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
                    <a href="{{ BeUrl(config('___SC___.info.alias')) }}" class="btn btn-default btn-flat btn-reset">{{ trans('global.act_back') }}</a>
                  
                </div><!-- /.box-body -->
              </div>
        </div>
    </div>

</form>
@stop

@push('style')
<style>
#imagePreview {
    width: 100%;
}
#inputImage {
    height: 0;
    width: 0;
    visibility: hidden;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('/global/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
tinymce.init({ 
    selector:'textarea',
    height: 300,
    theme: 'modern', 
    plugins: [
        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen',
        'insertdatetime media nonbreaking save table contextmenu directionality',
        'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
    ],
    toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
    toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
    image_advtab: true,
    content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tinymce.com/css/codepen.min.css'
    ] 
});
</script>
@endpush