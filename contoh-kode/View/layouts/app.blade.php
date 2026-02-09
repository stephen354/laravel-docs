{{--
    CONTOH LAYOUT - layouts/app.blade.php
    
    Layout utama yang digunakan oleh semua halaman.
    Halaman lain akan @extends layout ini.
--}}

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title dengan fallback --}}
    <title>@yield('title', 'Laravel App') | {{ config('app.name') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- CSS Bootstrap (CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        .navbar-brand {
            font-weight: bold;
        }
    </style>

    {{-- Stack untuk CSS tambahan dari child view --}}
    @stack('styles')
</head>

<body>
    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                🚀 Laravel App
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            Home
                        </a>
                    </li>

                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                                href="{{ route('products.index') }}">
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                                href="{{ route('categories.index') }}">
                                Categories
                            </a>
                        </li>
                    @endauth
                </ul>

                <ul class="navbar-nav">
                    @guest
                        {{-- Menu untuk Guest --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        {{-- Menu untuk User yang sudah login --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        Profile
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0">
                &copy; {{ date('Y') }} Laravel App.
                Dibuat dengan ❤️ menggunakan Laravel {{ app()->version() }}
            </p>
        </div>
    </footer>

    {{-- JavaScript Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Setup CSRF untuk AJAX --}}
    <script>
        // Setup untuk fetch/axios
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    </script>

    {{-- Stack untuk JS tambahan dari child view --}}
    @stack('scripts')
</body>

</html>
