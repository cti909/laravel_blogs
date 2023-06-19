@extends('layout')
@section('body')
    <section style="background-image: url('{{ asset('image/background_login.jpg') }}'); height: calc(100vh);">
        <div class="container h-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem; opacity: 0.8;">
                        <div class="card-body p-5">
                            <div class="mb-md-5 mt-md-4 pb-3">
                                <h1 class="fw-bold mb-2 text-uppercase text-center">Login</h1>
                                <p class="text-white-50 mb-4  text-center">Please enter your login and password!</p>
                                <form id="login-form" method="POST" action="{{ route('accounts.loginCheck') }}">
                                    @csrf
                                    <div class="form-outline form-white mb-3">
                                        <label class="form-label" for="username">Username</label>
                                        <input name="username" id="username" class="form-control form-control-lg"
                                            placeholder="Enter Username" value="{{ old('email') }}" />
                                        @error('name')
                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-outline form-white mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <input name="password" id="password" type="password"
                                            class="form-control form-control-lg" placeholder="Enter Password" />
                                        @error('name')
                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-secondary form-control form-control-lg mt-3">
                                        <strong>Sign in</strong>
                                    </button>
                                </form>
                                <div class="d-flex justify-content-between mt-2">
                                    <a class="text-white-50" href="#">Forgot password?</a>
                                    <a class="text-white-50" href="{{ route('homepage.home') }}">Back to home</a>
                                </div>
                            </div>
                            <div>
                                <p class="mb-4 text-center">You have an account?
                                    <a href="{{ route('accounts.registerForm') }}" class="text-white-50 fw-bold">Sign Up</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        /**
         * check username, password is null
         */
        let username = document.getElementById("username");
        let password = document.getElementById("password");
        let form = document.getElementById('login-form');
        form.addEventListener('submit', function(event) {
            if (username.value == "" || password.value == "") {
                event.preventDefault();
                alert("You must enter full information!");
            }
        });
        /**
         * check error from server
         */
        @if ($errors->any())
            let str = "";
            @foreach ($errors->all() as $error)
                str += "{{ $error }} \n";
            @endforeach
            // load page -> display alert
            $(document).ready(function() {
                alert(str);
            });
        @endif
    </script>
@endsection
