<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <link href="{{ asset('bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome6/css/all.min.css') }}" rel="stylesheet">
    @yield('css')
</head>

<body>
    @yield('body')
    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap5/js/bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('bootstrap5/js/bootstrap.bundle.js') }}"></script> --}}
    @yield('js')
</body>

</html>
