<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.title') }}</title>
  </head>
  <body style="background: #EAEAEA;padding: 25px 0;">
    <div style="background: #FFF; width: 90%; margin: 25px auto;">
        <h1 style="background: #3c8dbc; color: #FFF; padding: 10px 20px; font-size: 18px;">{{ config('app.title') }}</h1>
        <div style="padding: 20px;">
            @yield('content')
        </div>
    </div>
  </body>
</html>