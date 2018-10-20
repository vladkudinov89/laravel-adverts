<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Adverts</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js' , 'build') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ mix('css/app.css' , 'build') }}" rel="stylesheet">
</head>
<body id="app">
<header>
    <nav class="navbar navbar-expand-md  navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                Adverts
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item">
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('admin.home')  }}">Admin</a>
                                <a class="dropdown-item" href="{{ route('cabinet')  }}">Cabinet</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="app-content py-3">
    <div class="container">

        {{--@section('breadcrumbs')--}}
        {{--<ul class="breadcrumb">--}}
        {{--<li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>--}}
        {{--<li class="breadcrumb-item"><a href="{{route('login')}}">Login</a></li>--}}
        {{--<li class="breadcrumb-item active">Reset</li>--}}
        {{--<li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>--}}
        {{--</ul>--}}
        {{--@endsection--}}

        @section('breadcrumbs' , Breadcrumbs::render())
        @yield('breadcrumbs')
        @include('layouts.partials.flash')
        @yield('content')
    </div>
</main>

<footer>
    <div class="container">
        <div class="border-top pt-3">
            <p>&copy; {{date('Y')}} - Adverts</p>
        </div>
    </div>
</footer>

</body>
</html>
