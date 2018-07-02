<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'Sample App')</title>
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
  </head>
  <body>
    @include('layouts._header')

    <div class="container">
      <div class="col-md-offset-1 col-md-10">
        @include('shared._messages')
        @yield('content')
        @include('layouts._footer')
      </div>
    </div>
  </body>
</html>