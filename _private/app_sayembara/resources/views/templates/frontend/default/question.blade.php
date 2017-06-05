@extends( config('app.template') . 'layouts.master')

@section('content')
<main class="mdl-layout__content mdl-color--grey-100">
    <div class="mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-grid">

    <!-- Container for the demo -->
    <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
        <div class="mdl-card__title mdl-color--light-blue-600 mdl-color-text--white">
        <h2 class="mdl-card__title-text">Answer the questions</h2>
        <span id="time_spent" style="position: absolute;right: 5px;font-size: 12px;">00:00:00</span>
        </div>
        <div class="mdl-card__supporting-text mdl-color-text--grey-600">

            <form action="{{ url('save-answer') }}" method="POST" class="form-question">

                <div id="form-question">
                    <?php $questions = []?>
                    @if(!empty($rows))
                    @foreach($rows as $k=>$r)
                    <?php 
                        $questions[]= val($r, 'id');
                        $pilihan = json_decode(val($r, 'pilihan'), true); 
                        $soal = val($pilihan, 'soal'); uksort($soal, function() { return rand() > rand(); });
                    ?>
                        <h3></h3>
                        <section>
                            <p>{{ val($r, 'soal') }}</p>
                                @foreach($soal as $kO=>$vO)
                                @if($vO)
                                    <label class="mdl-radio mdl-js-radio" for="option{{$k}}-{{$kO}}">
                                        <input type="radio" name="answers[{{ val($r, 'id') }}]" id="option{{$k}}-{{$kO}}" class="mdl-radio__button" value="{{$kO}}">
                                        <span class="mdl-radio__label">{{$vO}}</span>
                                    </label><br/>
                                @endif
                                @endforeach
                        </section>
                    @endforeach
                    @endif
                </div>
                <input type="hidden" value="{{ json_encode($questions) }}" name="_questions"/>
                <input type="hidden" value="{{ csrf_token() }}" name="_token"/>
            </form>

        </div>
    </div>
    </div>
</main>
@stop

@push('styles')
<link href="{{ $pub_url }}/css/wizard.css" rel="stylesheet"/>
<style>
.choice{
    list-style: none !important;
    margin: 0;
    padding: o;
}
</style>
@endpush
@push('scripts')
<script src="{{ $pub_url }}/js/jquery.steps.min.js"></script>
<script>
    $("#form-question").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true
    });
    $('.number').each(function(i){
        if((i+1)>=10)
        {
            $(this).css('margin-left', '-5px');
        }
    });
    $(document).on('click', '[href="#finish"]', function(){

        $('.form-question').attr('done', true);
        $('.form-question').submit();
        
        return false;
    });

    ping();
    setInterval(function(){
        ping();
    },1000);

    function ping()
    {
        $.getJSON('{{url('ping')}}', function(d){
            $('#time_spent').html(d.time_spent)
        });
    }
</script>
@endpush