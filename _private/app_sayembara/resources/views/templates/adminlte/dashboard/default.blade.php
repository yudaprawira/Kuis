@extends( config('app.be_template') . 'layouts.master')

@section('content')
    <!-- Main content -->
    <section class="content" id="content-dashboard">
    <div class="overlay">
      <!-- Small boxes (Stat box) -->
      
      <div class="text-welcome">
        Hai<br />
        Selamat Datang di              
        <h3>{{ config('app.title') }}</h3>
      </div>
                        
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ $totalMember }}</h3>
              <p>Member</p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <a href="{{ url('member') }}" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{ $totalBook }}</h3>
              <p>Total Buku</p>
            </div>
            <div class="icon">
              <i class="fa fa-book"></i>
            </div>
            <a href="{{ url('book') }}" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{ $totalRating }}</h3>
              <p>Total Rating</p>
            </div>
            <div class="icon">
              <i class="fa fa-star"></i>
            </div>
            <a href="{{ url('rating') }}" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div><!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{ $totalTransaksi }}</h3>
              <p>Total Transaksi</p>
            </div>
            <div class="icon">
              <i class="fa fa-money"></i>
            </div>
            <a href="{{ url('pesanan') }}" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div><!-- ./col -->
      </div><!-- /.row -->
      
    </div>
    </section><!-- /.content -->
<style>
.text-welcome {
    text-align: center;
    font-size: 19px;
    padding: 30px 0;
    
}
</style>
@stop
@push('scripts')

@endpush