@extends( config('app.template') . 'layouts.master')

@section('content')
<main class="mdl-layout__content mdl-color--grey-100">
    <div class="mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-grid">
        <div class="mdl-layout-spacer"></div>
        <!-- Container for the demo -->
        <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-cell--5-col-desktop">
            <div class="mdl-card__title mdl-color--light-blue-600 mdl-color-text--white">
            <h2 class="mdl-card__title-text">PREPARATION</h2>
            </div>
            <div class="mdl-card__supporting-text mdl-color-text--grey-600">
                <ul>
                    <li>Ketentuan hasil berdasarkan nilai, durasi dan tanggal submit</li>
                    <li>Ranking tertinggi ditentukan dari nilai yang paling tinggi</li>
                    <li>Jika ada nilai tertinggi yang sama, maka yang terpilih adalah dengan durasi terpendek (yang paling cepat menyelesaikannya)</li>
                    <li>Jika ada nilai tertinggi yang sama dan durasi yang sama, maka yang terpilih adalah yang pertama kali submit</li>
                    <li>Ujian dibatasi sehari hanya satu kali percobaan</li>
                </ul>
            
                <!-- Button that handles sign-in/sign-out -->
                <div style="text-align: center;padding: 30px 0 0;">
                    <a class="mdl-button mdl-js-button mdl-button--raised" href="{{ url('start') }}">Start Exam</a>
                </div>

            </div>
        </div>
        <div class="mdl-layout-spacer"></div>
    </div>
</main>
@stop
@push('styles')
@endpush

@push('scripts')
<script>
@if(val($_GET,'msg'))
swal({!!base64_decode(val($_GET,'msg'))!!});
@endif
</script>
@endpush