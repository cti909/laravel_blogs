@extends('layout')
@section('body')
    <section style="background-image: url('{{ asset('image/background_register.jpg') }}'); height: 100vh;">
        <div class="container h-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                    <div class="card p-5 bg-dark text-white" style="border-radius: 15px; opacity: 0.8;">
                        <h1 class="text-uppercase text-center mb-3">Create new account</h1>
                        <form method='POST' id="register-form" action="{{ route('accounts.accountCreate') }}">
                            @csrf
                            <div class="form-outline mb-3">
                                <label class="form-label" for="name">Your Name</label>
                                <input name='name' type="text" id="name" class="form-control form-control-lg"
                                    placeholder="Your Name" value="{{ old('name') }}" />
                            </div>
                            <div class="form-outline mb-3">
                                <label class="form-label" for="username">Username</label>
                                <input name='username' type="text" id="username" class="form-control form-control-lg"
                                    placeholder="Username" value="{{ old('username') }}" />
                            </div>
                            <div class="form-outline mb-3">
                                <label class="form-label" for="password">Password</label>
                                <input name='password' type="password" id="password" class="form-control form-control-lg"
                                    placeholder="Password" value="{{ old('password') }}" />
                            </div>
                            <div class="form-outline mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input name='email' type="email" id="email" class="form-control form-control-lg"
                                    placeholder="Email" value="{{ old('email') }}" />
                            </div>
                            <div class="form-outline mb-3">
                                <label class="form-label" for="address">Address</label>
                                <input name='address' type="text" id="address" class="form-control form-control-lg"
                                    placeholder="Address" value="{{ old('address') }}" />
                            </div>
                            <button type="submit" class="btn btn-secondary form-control form-control-lg mt-3">
                                <strong>Register</strong>
                            </button>
                        </form>
                        <div class="d-flex justify-content-end mt-2">
                            <a class="text-white-50" href="{{ route('homepage.home') }}">Back to home</a>
                        </div>
                        <p class="mb-4 text-center mt-4">Don't have an account?
                            <a href="{{ route('accounts.loginForm') }}" class="text-white-50 fw-bold">Sign In</a>
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
         * check form is validate
         */
        let name = document.getElementById("name");
        let username = document.getElementById("username");
        let password = document.getElementById("password");
        let email = document.getElementById("email");
        let address = document.getElementById("address");
        let form = document.getElementById('register-form');
        form.addEventListener('submit', function(event) {
            if (name.value == "" || username.value == "" || password.value == "" ||
                email.value == "" || address.value == "") {
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
