<ul id="text-message-notif" style="display: none;">
    @if( !empty($notif) ) 
        @foreach( $notif as $n )
        <li data-type="{{ $n['type'] }}" data-align="{{ $n['align'] }}" data-width="{{ $n['width'] }}" data-close="{{ $n['close'] }}" data-name="{{ $n['name'] }}">{!! $n['text'] !!}</li>
        @endforeach
    @endif
</ul>