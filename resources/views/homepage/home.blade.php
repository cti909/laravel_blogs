@extends('layout')
@section('body')
    @include('navbar')
    <div class='bg-image'
        style="background-image : url('{{ asset('image/background_home.jpg') }}'); height: 100vh; object-fit: cover;">
        <div class="container vh-100 d-flex align-items-center justify-content-center">
            <h1 class="text-white" style="font-size: 64px">Welcome to web</h1>
        </div>
    </div>
    @include('footer')
@endsection
@section('js')
    <script>
        @if (session('success'))
            let successMessage = "{{ session('success') }}";
            // load page -> display success message
            $(document).ready(function() {
                alert(successMessage);
            });
        @endif
    </script>
@endsection
