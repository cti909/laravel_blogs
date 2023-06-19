<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Blogs</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.index') }}">Blogs</a>
                    </li>
                </ul>
                @guest
                    <a type="button" class='btn btn-primary p-2 mx-2'
                        href="{{ route('accounts.registerForm') }}">Register</a>
                    <a type="button" class='btn btn-primary p-2 mx-2' href="{{ route('accounts.loginForm') }}">Login</a>
                @else
                    <strong class="text-white">Welcome:
                        <span class='text-success me-2'> {{ Auth::user()->name }}</span>
                    </strong>
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-gear"></i>
                        </a>
                        <ul class="dropdown-menu" style="left: -85px; top:100%;" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="">My account</a></li>
                            <li><a class="dropdown-item" href="#">Helps</a></li>
                            <li><a class="dropdown-item" href="">Reset password</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item btn-primary" href="{{ route('accounts.logout') }}">Log out</a></li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </nav>
</header>
