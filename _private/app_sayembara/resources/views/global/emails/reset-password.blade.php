@extends( 'global.emails.master')

@section('content')
    {!! Lang::get('login.email_reset', ['username'=>$username, 'link'=>$link]) !!} 
@stop