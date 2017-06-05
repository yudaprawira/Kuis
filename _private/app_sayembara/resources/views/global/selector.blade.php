<!-- Modal -->
<div class="modal fade modalImageSelector" id="modalImageSelector" tabindex="-1" role="dialog" aria-labelledby="modalImageSelector-Label" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content box">
    <div class="modal-body no-padding" style="padding-top: 17px!important;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-right: 10px;margin-top: -15px;">&times;</button>
        
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" style="padding-left: 20px;margin: 5px 0px;">
            <li role="presentation" class="active"><a class="no-radius" href="#tab-pustaka" aria-controls="tab-pustaka" role="tab" data-toggle="tab">{{ trans('pustakagambar::global.title') }}</a></li>
            <li role="presentation"><a class="no-radius" href="#tab-upload" aria-controls="tab-upload" role="tab" data-toggle="tab">{{ trans('pustakagambar::global.upload') }}</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tab-pustaka" style="background: #ececec;">
                <div class="text-center">
                    <div id="imageSelectorPreloader">
                        
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-upload">
                <div class="box" style="border: none;">
                    <div class="box-body">
                        
                        <form method="POST" class="form-ajax" action="{{ BeUrl(config('pustakagambar.info.alias').'/save') }}" id="uploader_formData" <form method="POST" action="{{ BeUrl(config('pustakagambar.info.alias').'/save') }}" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box-body">
                                        <div class="text-center">
                                            <img  src="{{ asset('/global/images/no-image.png') }}" id="uploader_imagePreview" style="width: 100%;"><br/><br/>
                                            <a href="#" class="btn btn-sm btn-flat btn-default" id="uploader_changeImage"><i class="fa fa-pencil"></i> {{ trans('pustakagambar::global.change_image') }} </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    
                                    <div class="form-group has-feedback">
                                        <label>{{ trans('pustakagambar::global.copyright') }}</label><span class="char_count"></span>
                                        <input type="text" class="form-control" name="copyright" placeholder="&copy;" maxlength="50" value="" />
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label>{{ trans('pustakagambar::global.keterangan') }}</label><span class="char_count"></span>
                                        <input type="text" class="form-control" name="keterangan" maxlength="125" value="" />
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label>{{ trans('pustakagambar::global.kategori') }}</label><span class="char_count"></span>
                                        <input type="text" class="form-control" name="kategori" maxlength="50" value="{{$path}}" />
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('global.act_add') }}</button>
                                    <a href="{{ BeUrl(config('pustakagambar.info.alias').'/edit/0') }}" class="btn btn-default btn-flat btn-reset">{{ trans('global.act_cancel') }}</a>
                                    
                                </div>
                            </div>
                            

                            <input type="hidden" name="id" value="" />
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="file" name="image" id="uploader_inputImage" style="height: 0; width: 0;"/>
                        </form>
                        
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
        
    </div>
    <div class="overlay imageselector-loding" style="display: none;">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
    </div>
</div>
</div>


<style>
#imageSelectorPreloader{
    padding: 30px;
    font-size: 35px;
}
</style>
<script>
    $(document).ready(function(){
        //replacer
        $('#modalImageSelector').on('shown.bs.modal', function(){
            if ( $('#imageSelectorPreloader').length>0 )
            {
                loadImageSelector('{{ BeUrl(config('pustakagambar.info.alias').'/lookup') }}');
            }
        });

        $(document).on('change', '#uploader_inputImage', function(){
            $(this).closest('form').find('[name=keterangan]').val(this.files[0].name.split('.')[0]);
        });

        $(document).on('click', '#imageSelectorLibrary .pagination li a', function(){
            loadImageSelector($(this).attr('href'));
            return false;
        });
        $(document).on('submit', '#imageSelectorLibrary .form-filter', function(){
            var url = '{{ BeUrl(config('pustakagambar.info.alias').'/lookup') }}';
                url+= '?' + $(this).serialize();
            loadImageSelector(url);
            return false;
        });
        $(document).on('click', '#imageSelectorLibrary .select-image', function(){
            var url = $(this).data('url');
            var path = $(this).data('path');
            var copyright = $(this).data('copyright');
            var description = $(this).data('description');
            selectedImage(url, path, copyright, description);
            return false;
        });

        function loadImageSelector ( url )
        {
            overlay = $('.imageselector-loding');

            $.ajax({
                type		: 'GET',
                url			: url,
                cache       : false,
                contentType : false,
                beforeSend	: function(xhr) { overlay.show(); },
                error: errorHandler = function() {
                    alert('Something went wrong!');
                },
                success		: function(dt){
                    
                    if ( $('#imageSelectorPreloader').length>0 )
                        $('#imageSelectorPreloader').replaceWith(dt); 
                    else
                        $('#imageSelectorLibrary').replaceWith(dt); 

                },
            }).done(function(){ overlay.hide(); loading(0); }); 
        }
    });
    function imgSelector(targetUrl, targetPath, targetCopy, targetDesc)
    {
        $('#modalImageSelector').attr('target-url', targetUrl);
        $('#modalImageSelector').attr('target-path', targetPath);
        $('#modalImageSelector').attr('target-copyright', targetCopy);
        $('#modalImageSelector').attr('target-description', targetDesc);
        $('#modalImageSelector').modal('show');
    }
</script>