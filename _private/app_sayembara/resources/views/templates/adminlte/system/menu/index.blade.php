@extends( config('app.be_template') . 'layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Menu</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="dd" id="nestableMenu">
                    {!! $list !!}
                  </div>
                </div><!-- /.box-body -->
              </div>
              
        </div>
        <div class="col-md-4">
            {!! $form !!}
        </div>
    </div>
@stop

@push('style')
    <link rel="stylesheet" href="{{ asset('/global/css/nestable.css') }}"/>
    <link rel="stylesheet" href="{{ $pub_url }}/plugins/iCheck/square/blue.css">
@endpush
@push('scripts')
<script src="{{ $pub_url }}/plugins/iCheck/icheck.min.js"></script>
<script src="{{ asset('/global/js/jquery.nestable.js') }}"></script>
<script>
$(function() {
    initNestabled();
    initCheckbox();
    
    //SUBMIT
    $(document).on('submit', '#formDataMenu', function(){
        
        var form = $(this);
        
        $.ajax({
    		type		: 'POST',
    		url			: form.attr('action'),
            data        : form.serialize(),
    		beforeSend	: function(xhr) { loading(1) },
    		success		: function(dt){

              if ( dt.status ) initNestabled();
              
              if ( dt.form ) form.closest('.box').replaceWith(atob(dt.form));

              if ( dt.list ) $('#nestableMenu').html(atob(dt.list));

              if ( dt.message ) initNotif(atob(dt.message));
              
              initNestabled();
              
    		},
    	}).done(function(){ loading(0);window.location.reload(); }); 
        
        return false;
    });
    
    //EDIT & RESET & DELETE
    $(document).on('click', '.menu-btn-edit, .menu-btn-reset, .menu-btn-delete', function(){
        
        var ButtonElm = $(this);
        var isDelete = ButtonElm.hasClass('menu-btn-delete');
        var url = ButtonElm.attr('href');
        var modal = $(ButtonElm.data('id')).find('.modalConfirmation');
        
          //close modal
          modal.find('.close').click();
        
        $.ajax({
    		type		: 'GET',
    		url			: url,
    		beforeSend	: function(xhr) { if ( isDelete ) { modal.find('.delete-loding').show(); } else { loading(1); } },
    		success		: function(dt){
    		  if ( isDelete )
              {
                  if ( dt.list ) $('#nestableMenu').html(atob(dt.list));
                  
                  initNestabled();
              }
              else
              {
    		      $('#formDataMenu').closest('.box').replaceWith(dt);
              } 
            },
    	}).done(function(){ if ( isDelete ) { modal.find('.delete-loding').hide();window.location.reload(); } else { loading(0); } });
        
        return false;
    });
    
    //ADD CHILD
    $(document).on('click', '.menu-btn-add', function(){
       var id = $(this).data('id'); 
       var nm = $(this).data('name');
       
       $('#data-parent span').text(nm);
       $('#data-parent input').val(id);
       $('#data-parent').show();
       
       $('#name').focus();
        
       return false; 
    });
    
    //SAVE ACCESS
    $(document).on('submit', '.form-access', function(){
       var form = $(this);
       var action = form.attr('action');
       var data  = form.serialize();
       var modal = form.closest('.modal');
       
       //close modal
       modal.find('.close').click();
      
        $.ajax({
    		type		: 'POST',
    		url			: action,
    		data		: data +'&_token={{ csrf_token() }}',
    		beforeSend	: function(xhr) { loading(1) },
    		success		: function(dt){},
    	}).done(function(){ loading(0) });
           
       return false; 
    });
});

function initNestabled()
{
    $('#nestableMenu').nestable().on('change', function(e){
       
       var list   = e.length ? e : $(e.target);
       updateMenuOrder(list.nestable('serialize'));
        
    });
}

function updateMenuOrder(data)
{
    $.ajax({
		type		: 'POST',
		url			: '{{ BeUrl("system-menu/order") }}',
		data		: 'data='+ JSON.stringify(data) +'&_token={{ csrf_token() }}',
		beforeSend	: function(xhr) { loading(1); },
		success		: function(dt){},
	}).done(function(){ loading(0); window.location.reload(); });
}

function initCheckbox()
{
    $('.checkAll').on('ifChecked', function(event){
      var form = $(this).closest('form');
      form.find('[type=checkbox]').iCheck('check');
    });
    $('.checkAll').on('ifUnchecked', function(event){
      var form = $(this).closest('form');
      form.find('[type=checkbox]').iCheck('uncheck');
    });
}

</script>
@endpush