@extends( config('app.template') . 'layouts.master')

@section('content')
<main class="mdl-layout__content mdl-color--grey-100">
    <div class="mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-grid">
    <div class="mdl-layout-spacer"></div>
    <!-- Container for the demo -->
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-cell--8-col-desktop">
        <div class="mdl-card__title mdl-color--light-blue-600 mdl-color-text--white">
            <h2 class="mdl-card__title-text">RESULT</h2>
        </div>
        <div class="mdl-card__supporting-text mdl-color-text--grey-600">
            <?php $result = json_decode(val($row, 'hasil'), true); ?>
            
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col-phone mdl-cell--4-col-tablet mdl-cell--9-col-desktop">
                    
                    @if ( val($result, 'all.percentage')>80 )
                    <h4>Congratulations, you <strong class="mdl-color-text--green">passed</strong> the exam</h4>
                    @else
                    <h4>You <strong class="mdl-color-text--red">failed</strong> the exam. Please try again.</h4>
                    @endif

                    <h6>Time Spent : <strong>{{ val($result, 'timing.time.string') }}</strong></h6>

                    <h6>Category result</h6>
                    <table class="mdl-data-table mdl-shadow--2dp mdl-cell--7-col-desktop">
                        @if( !empty(val($result, 'category')) )
                        @foreach(val($result, 'category') as $kC=>$valC)
                        <tr><td class="mdl-data-table__cell--non-numeric">{{$kC}}</td> <td>{{ val($valC, 'correct', '0') }}/{{ val($valC, 'total') }}</td></tr>
                        @endforeach
                        @endif
                    </table>
                </div>
                <div class="mdl-cell mdl-cell--2-col-phone mdl-cell--4-col-tablet mdl-cell--3-col-desktop mdl-typography--text-center">
                    <strong>Final Score</strong>
                    <div id="final-circle" data-percentage="{{val($result, 'all.percentage')}}"></div>
                </div>
            </div>

            @if ( !empty( val($result, 'detail') ) )
            <ul class="demo-list-three mdl-list">
                @foreach(val($result, 'detail') as $k=>$v)
                <li class="mdl-list__item mdl-list__item--three-line">
                    <span class="mdl-list__item-secondary-content" style="padding: 15px;">
                        @if ( val($v, 'status') )
                        <a class="mdl-list__item-secondary-action mdl-color-text--green" href="#"><i class="material-icons">check_circle</i></a>
                        @else
                        <a class="mdl-list__item-secondary-action mdl-color-text--red" href="#"><i class="material-icons">remove_circle</i></a>
                        @endif
                    </a>
                    </span>
                    <span class="mdl-list__item-primary-content">
                        <span>{{ val($v, 'question') }}</span>
                        <span class="mdl-list__item-text-body">
                            {{ val($v, 'answer') }}
                        </span>
                    </span>
                </li>
                @endforeach
            </ul>
            @endif

             <div style="float:left;padding: 30px 0 0;">
                <a class="mdl-button mdl-js-button mdl-button--raised" href="{{ url('hasil') }}">My Score</a>
            </div>
             <div style="float:right;padding: 30px 0 0;">
                <a class="mdl-button mdl-js-button mdl-button--raised" href="{{ url('ranking') }}">Ranking</a>
            </div>

            <div style="clear: both;"></div>

        </div>
    
    </div>
    <div class="mdl-layout-spacer"></div>
    </div>
</main>
@stop

@push('styles')
<link href="{{ $pub_url }}/css/jquery.circliful.css" rel="stylesheet"/>
<style>
</style>
@endpush
@push('scripts')
<script src="{{ $pub_url }}/js/jquery.circliful.min.js"></script>
<script>
    $(document).ready(function(){
        $("#final-circle").circliful({
            animation: 1,
            animationStep: 2,
            foregroundBorderWidth: 15,
            backgroundBorderWidth: 15,
            percent: $("#final-circle").data('percentage'),
            textSize: 28,
            textStyle: 'font-size: 12px;',
            textColor: '#666',
            multiPercentage: 1,
            percentages: [10, 20, 30]
        });
    });
</script>
@endpush