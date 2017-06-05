@extends( config('app.be_template') . 'layouts.master')

@section('content')
    <!-- Main content -->
    <section class="content">

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" class="flat" href="#tab-meja">MEJA</a></li>
            <li><a data-toggle="tab" class="flat" href="#tab-pesanan">PESANAN</a></li>
        </ul>

        <div class="tab-content">
            <div id="tab-meja" class="tab-pane fade in active">
                <div class="row-fluid list-meja">
                    <h1 class="text-center">SEDANG MEMUAT...</h1>
                </div>
                <div class="clearfix"></div>
            </div>
            <div id="tab-pesanan" class="tab-pane fade">
                pesanan
            </div>
        </div>

        <!-- MODAL MEJA -->
        <div id="modalMeja" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">
                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Info Meja</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover table-striped">
                        <tr>
                            <td rowspan="4" style="width: 126px;"><img src="" id="mod-meja-foto" style="width: 126px;"></td>
                        </tr>
                        <tr>
                            <td style="width:10px;">ID</td> <td id="mod-meja-id">-</td>
                        </tr>
                        <tr>
                            <td style="width:10px;">Nama</td> <td><input type="text" class="form-control" id="mod-meja-nama"></td>
                        </tr>
                        <tr>
                            <td style="width:10px;">Status</td> <td>
                                <input type="radio" name="mod-meja-status" class="excheck" id="mod-meja-status-dipakai" value="dipakai"> Dipakai &nbsp; &nbsp; &nbsp;
                                <input type="radio" name="mod-meja-status" class="excheck" id="mod-meja-status-tersedia" value="tersedia"> Tersedia 
                                <span id="isOnline"></span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default flat" data-dismiss="modal">Tutup</button>
                    <button type="button" id="mod-meja-simpan" class="btn btn-primary flat" data-dismiss="modal">Simpan Perubahan</button>
                </div>
                </div>
            </div>
        </div>

    </section>
@stop
@push('style')
<style>
.tab-pane{
    background: #fff;
    border-left: 1px solid #ddd;
    padding: 10px;
    border-bottom: 1px solid #ddd;
    border-right: 1px solid #ddd;
}
.list-meja .item{
    border: 1px solid #ddd;
    margin: 5px;
    padding: 35px 20px;
    text-align: center;
    cursor: pointer;
    font-weight: bold;
    text-transform: uppercase;
}
.list-meja .item:hover,
.list-meja .item.active {
    background: #00a65a;
    color: #FFF;
}
</style>
@endpush

<?php 
    $resto_meja  = json_decode(val($active_state, 'meja'), true); 
    $data_meja   = array();
    for($i=1; $i<=val($resto_meja, 'standart');$i++)
    {
        $data_meja[$i] = ['nama'=>'Meja '.$i, 'status'=>'tersedia', 'uid'=>'-'];
    }
    $data_meja = json_encode($data_meja);

    $data_user = json_encode([
        session::get('ses_userid') => [
            'nama' => session::get('ses_username'),
            'foto' => session::get('ses_photo'),
            'online' => true,
            'last_online' => dateSql(),
        ],
    ]);
?>

@push('scripts')
<script>
$(document).ready(function(){

    var dataMeja = new Array();
    for(var i=1; i<={{val($resto_meja, 'standart')}};i++)
    {
        dataMeja[i] = { nama:'Meja '+i, status:'tersedia', member_id:'-' };
    }

    var ypResto = new ypFireBaseResto('{{val($active_state, 'url')}}', 'meja');

    //DATA MEJA
    var row = ypResto.getMeja().orderByChild('id');
        row.once('value', function (snapshot) {
            var html = '';
            var idMeja = [];
            if (snapshot.hasChildren()) 
            {
                snapshot.forEach(function (child) {
                    var id = child.getKey();
                    var obj = child.val();

                    if ( dataMeja[id] )
                    {
                        dataMeja[id] = obj;
                        idMeja.push(id);
                    }
                    else
                    {
                        child.ref.remove();
                    }
                });
            }
            if ( idMeja.length != {{val($resto_meja, 'standart')}} )
            {
                ypResto.dataMeja(dataMeja);
            }
        });

        row.on('value', function (snapshot) {
            listMeja(snapshot);   
        });

        function listMeja(snapshot)
        {
            var html = '';
            if (snapshot.hasChildren()) 
            {
                snapshot.forEach(function (child) {
                    var id = child.getKey();
                    var obj = child.val();

                    html+='<div class="col-xs-6 col-md-2"><div data-meja_id="'+id+'" data-member_id="'+obj.member_id+'" data-nama="'+obj.nama+'"  data-status="'+obj.status+'"  data-waktu="'+obj.waktu+'" data-toggle="modal" data-target="#modalMeja" class="item '+ (obj.status=='tersedia' ? '' : 'active') +'">'+ obj.nama +'</div></div>';
                });
                $('.list-meja').html(html);
            } 
        }

    //POPUP OPEN
    $(document).on('click', '.list-meja .item', function(){
        var nama = $(this).data('nama');
        var meja = $(this).data('meja_id');
        var status = $(this).data('status');
        var waktu = $(this).data('waktu');
        var member_id = $(this).data('member_id');

        $('#mod-meja-id').html(nama);
        $('#mod-meja-nama').val("Tamu");
        $('#mod-meja-nama').attr('readonly', false);
        $('#mod-meja-simpan').attr("meja-id", meja);
        $('#mod-meja-foto').attr('src', '{{asset('/global/images/no-image.png')}}');
        $('#isOnline').html('');

        if ( member_id )
        {
            var ypMember = new ypFireBase('member');
            var rowMember = ypMember.get(member_id);
            rowMember.on('value', function(snapshot){
                var r = snapshot.val();
                if ( snapshot.numChildren()>0 )
				{
                    if ( r.online )
                        $('#isOnline').html('<span class="pull-right label label-success">ONLINE</span>');
                    else
                        $('#isOnline').html('<span class="pull-right label label-danger">OFFLINE</span>');
                    
                    $('#mod-meja-nama').val(r.nama);
                    $('#mod-meja-nama').attr('readonly', true);
                    $('#mod-meja-foto').attr('src', (r.foto == '-'? '{{asset('/global/images/no-image.png')}}' : r.foto ));
                }
            });
        }
        
        if ( waktu!=='undefined' )
            $('#mod-meja-id').html(nama+'<div class="pull-right"><strong>Terakhir dipakai: </strong>'+waktu+'</div>');


        $('[name="mod-meja-status"]').attr('checked', false);
        $('[name="mod-meja-status"]').prop('checked', false);

        if ( status=='dipakai' )
        {
            $('#mod-meja-status-dipakai').attr('checked', true);
            $('#mod-meja-status-dipakai').prop('checked', true);
        }
        else
        {
            $('#mod-meja-status-tersedia').attr('checked', true);
            $('#mod-meja-status-tersedia').prop('checked', true);
        }

        return false;
    });

    //Even tersedia/tidak
    $(document).on('change', '[name="mod-meja-status"]', function(){
        var isTersedia = $(this).val()=='tersedia' ? true : false;
        
        if ( isTersedia )
        {
            //set temp
            if ( !$('#mod-meja-foto').attr('temp-src') ) $('#mod-meja-foto').attr('temp-src', $('#mod-meja-foto').attr('src'));
            if ( !$('#mod-meja-nama').attr('temp-val') ) $('#mod-meja-nama').attr('temp-val', $('#mod-meja-nama').val());

            $('#mod-meja-foto').attr('src', '{{asset('/global/images/no-image.png')}}');
            $('#mod-meja-nama').val('Tamu');
            $('#mod-meja-nama').attr('readonly', false);
        }
        else
        {
            $('#mod-meja-nama').attr('readonly', true);
            if ( $('#mod-meja-foto').attr('temp-src') ) $('#mod-meja-foto').attr('src', $('#mod-meja-foto').attr('temp-src'));
            if ( $('#mod-meja-nama').attr('temp-val') && $('#mod-meja-nama').attr('temp-val')!='Tamu') $('#mod-meja-nama').val($('#mod-meja-nama').attr('temp-val'));
        }

    });

    //simpan
    $(document).on('click', '#mod-meja-simpan', function(){
        var mejaID = $(this).attr('meja-id');
        var ypResto = new ypFireBaseResto('{{val($active_state, 'url')}}', 'meja/');

        var data = {
            'status' : $('[name="mod-meja-status"]:checked').val(),
            'waktu'  : $('[name="mod-meja-status"]').val() == 'dipakai' ? '{{dateSQL()}}' : ''
        };

        if ( data.status == 'tersedia' )
        {
            data.member_id = '-';
        }
        
        ypResto.update(mejaID, data);
    });

    //DATA PELAYAN
    var ypUser = new ypFireBaseResto('{{val($active_state, 'url')}}', 'user/{{session::get('ses_level_url')}}');
    ypUser.pelayan({!!$data_user!!});

});
</script>
@endpush