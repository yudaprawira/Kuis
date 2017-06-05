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
        <div class="mdl-card__supporting-text mdl-color-text--grey-600" style="padding:0;width:100%;">
            
            <div class="mdl-grid" style="padding:0;overflow-x: auto;">
                @if( !empty($rows) )
                <table class="mdl-data-table mdl-shadow--2dp mdl-cell--12-col-desktop" style="width: 100%;">
                    <thead>
                        <th class="mdl-data-table__cell--non-numeric">Date</th> 
                        <th class="mdl-data-table__cell--non-numeric">Answer</th> 
                        <th class="mdl-data-table__cell--non-numeric">Score</th> 
                        <th class="mdl-data-table__cell--non-numeric">Time Spent</th> 
                        <th>&nbsp;</th> 
                    </thead>
                    <tbody>
                        @foreach($rows as $r )
                        <?php $result = json_decode(val($r, 'hasil'), true); ?>
                        <tr>
                            <td class="mdl-data-table__cell--non-numeric">{{ date("d M Y H:i:s", strtotime(val($r, 'tanggal'))) }}</td>
                            <td class="mdl-data-table__cell--non-numeric">{{ val($result,'all.correct', '0') .' of '. val($result,'all.total') }}</td>
                            <td class="mdl-data-table__cell--non-numeric">{{ val($r,'nilai') }}</td>
                            <td class="mdl-data-table__cell--non-numeric">{{ val($result,'timing.time.string') }}</td>
                            <td style="width: 10px;"><a href="{{ url('hasil/'.val($r, 'id')) }}" class="mdl-button" target="_blank">Result</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div style="text-center">
                    <a href="{{ url('') }}" class="mdl-button mdl-js-button mdl-button--raised">Start Exam</a>
                </div>
                @endif
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

@endpush