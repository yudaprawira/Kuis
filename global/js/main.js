$(document).ready(function(){
  
  initNotif(null);
  //initNumber();
  initCharacterCount();
  initPlugin();
  
  //Date
  if ( $('.tDate').length>0 )
  {
    $('.tDate').datepicker({
        'format' : 'dd M yyyy',
    });
  }
  
  //Time
  if ( $('.timepicker').length>0 )
  {
    initTimePicker();
  }

  //select relation
  if ( $('.relselect').length>0 )
  {
      $(document).on('change', '.relselect', function(){
        var url = $(this).data('url');
        var target = $(this).data('target');
        var _token = $(this).data('token');
        var selected = $(this).val();

        $.ajax({
            type		: 'POST',
            url			: url,
            data        : 'selected='+selected+'&_token='+_token,
            beforeSend	: function(xhr) { 
                if ( $(target).parent().find('.select-loader')>0 )
                {
                    $(target).parent().find('.select-loader').show();
                }
                else
                {
                    var loader = '<i class="fa fa-refresh fa-spin select-loader"></i>';
                    $(target).after(loader);
                }
             },
            success		: function(dt){
                $(target).html(dt);
                initPlugin();
            },
        }).done(function(){ $(target).parent().find('.select-loader').hide(); });

      });
  }

  //active parent menu
  $('.sidebar-menu>.treeview').each(function(){
    if ( $(this).find('.treeview-menu>.treeview.active').length>0 )
    {
        $(this).addClass('active');
        var menuParent = '<li class=""><a href="#">'+$(this).find('a:first>span').text().toLowerCase()+'</a></li>';
        $('.breadcrumb li:first').after(menuParent);
    }
  });
   
});

function initCharacterCount()
{
    $(document).on('keyup', 'input, textarea', function(){
        
        if( $(this).has('[maxlength]') )
        {
            var c = parseInt($(this).attr('maxlength')) - $(this).val().length;
            $(this).closest('.form-group').find('.char_count').text(c).removeClass('black').removeClass('red').addClass((c<=10?'red':'black'));
        }
        
    });
}

function readURL(input, preview) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(preview).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function initNotif(messages)
{  
    if ( messages )
    {
        $('#text-message-notif').replaceWith(messages);
    }
    
    //NOTIFICATION
    $('#text-message-notif li').each(function(i){
        
        var ntf_text = $(this).html();
        var ntf_type = $(this).data('type');
        var ntf_align = $(this).data('align');
        var ntf_width  = $(this).data('width');
        var ntf_close   = $(this).data('close');
        var ntf_tagName  = $(this).data('name');
        
        if ( i==0 )
        {
            $.bootstrapGrowl(ntf_text, {
                type: ntf_type,
                align: ntf_align,
                width: ntf_width,
                allow_dismiss: ntf_close
            });
        }
        else
        {
            setTimeout(function(){ 
                
               $.bootstrapGrowl(ntf_text, {
                    type: ntf_type,
                    align: ntf_align,
                    width: ntf_width,
                    allow_dismiss: ntf_close
               }); 
                
            }, (500*(i+1)) );
        }
        
        //highlight
        if ( ntf_tagName!='' && $('[name='+ntf_tagName+']').length>0 && $('[name='+ntf_tagName+']').parent().hasClass('has-feedback') )
        {
            var hasType = ntf_type=='danger' ? 'error' : ntf_type;
            $('[name='+ntf_tagName+']').parent().addClass('has-'+ hasType);
            $('[name='+ntf_tagName+']').addClass('inputHighlight').attr('data-hastype', 'has-'+ hasType);
        }
    
    });
    
    //remove Highlight
    $('.inputHighlight').on('blur', function(){
        $(this).removeClass('inputHighlight');
        $(this).parent().removeClass($(this).data('hastype'));
    });
    
    //focus
    //$('.inputHighlight:first').focus();
}

//FORMAT NUMBER
    numeral.register('locale', 'id', {
        delimiters: {
            thousands: '.',
            decimal: ','
        },
        abbreviations: {
            thousand: 'k',
            million: 'm',
            billion: 'b',
            trillion: 't'
        },
        ordinal : function (number) {
            return number === 1 ? 'er' : '�me';
        },
        currency: {
            symbol: 'Rp'
        }
    });
    numeral.locale('id');
    function initNumber(num, format, event)
    {
        if ( !event ) event = 'input';
        if ( !format ) format = '0,0';
        
        if ( num )
        {
            return numeral(num).format(format);
        }
        else
        {
            $(document).on(event, '.tNum', function(){
               $(this).val(numeral($(this).val()).format(format)); 
            });
            $(document).on(event, '.tDec', function(){
               $(this).val(numeral($(this).val()).format('0,0.00')); 
            });
        }
    }
    function stringToNum(form)
    {
        $('.tNum, .tDec').each(function(){
           $(this).val( numeral($(this).val()).value() ); 
        });        
    }
    function stringToNumForm(form)
    {
        form.find('.tNum').each(function(){
           $(this).val( numeral($(this).val()).value() ); 
        });
        form.find('.tDec').each(function(){
           $(this).val( numeral($(this).val()).value() ); 
        });
    }
//OEF FORMAT NUMBER

function loading(status)
{
    if ( status==1 )
    {
        $('#main-loding').show();
    }
    else
    {
        $('#main-loding').hide();
    }
}

function toRomawi(angka) 
{
  var desc = [1,4,5,9,10,40,50,90,100,400,500,900,1000];
  var roma = ["I","IV","V","IX","X","XL","L","XC","C","CD","D","CM","M"];
  var hasil='';
  
  for(var i=12; i >=0; i--) 
  {
     while(angka >= desc[i]) 
     {
    	angka-= desc[i];
    	hasil+= roma[i];
     }
  }
			
  return hasil;
}

function initTokenInput(target, limit, freeTag)
{
    var tokenSource = $(target).attr('data-source');
    $(target).tokenInput(tokenSource, {
        searchDelay: 2000,
        method: "GET",
        theme: "facebook",
        contentType: "json",
        queryParam: "q",
        searchDelay: 300,
        minChars: 1,
        tokenLimit:limit,
        preventDuplicates: true,
        prePopulate: $.parseJSON($(target).attr('data-populated'))
    });
}
function getValueTokenInput(target)
{
    var token = $(target).tokenInput("get");
    return token.length>0 ? token[0] : '';
}
function initPlugin()
{
    select2();
    $('input:not(.excheck)').iCheck({
    checkboxClass: 'icheckbox_square-green',
    radioClass: 'iradio_square-green',
    increaseArea: '20%' // optional
    });
}
function select2()
{
    $('.select2').select2();
} 
function initTimePicker()
{
    //Timepicker
    $('.timepicker').datetimepicker({
        datepicker:false,
        format:'H:i',
        minTime:'07:00',
        step:30
    });
}

function getDateTime()
{
    var date = new Date();

    return date.getFullYear() + '-' +
        ('00' + (date.getMonth()+1)).slice(-2) + '-' +
        ('00' + date.getDate()).slice(-2) + ' ' + 
        ('00' + date.getHours()).slice(-2) + ':' + 
        ('00' + date.getMinutes()).slice(-2) + ':' + 
        ('00' + date.getSeconds()).slice(-2);
}