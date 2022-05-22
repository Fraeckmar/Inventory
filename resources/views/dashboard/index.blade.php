<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ App\Http\Controllers\Settings::get('app_name') }}</title>

    {{-- Fav Icon --}}
    @php
        $com_logo = asset('img/logo.png');
        if (!empty(App\Http\Controllers\Settings::get('app_logo'))) {
            $com_logo = URL::to('images/'.App\Http\Controllers\Settings::get('app_logo'));
        }
    @endphp
    <link rel="shortcut icon" href="{{ $com_logo }}">
    <link rel="icon" type="image/png" href="{{ $com_logo }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.min.js') }}" defer></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}" defer></script>
    
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet">
    <link href="{{ asset('selectize/css/selectize-helper.css') }}" rel="stylesheet">
</head>
<body class="dashboard bg-white h-screen antialiased leading-none font-sans">
    <header>
        <div class="fixed bg-gray-800 w-full py-4 top-0 z-20">
            <div class="mx-auto flex justify-between items-center px-6 relative">
                <div>
                    <a href="{{ url('dashboard') }}" class="text-lg font-semibold text-gray-100 no-underline">
                        <img src="{{ $com_logo }}" alt="Inventory settings" class="max-w-[60px]">                  
                    </a>
                </div>
                <nav class="space-x-4 text-gray-300 text-sm sm:text-base">
                    @guest
                        <a class="no-underline hover:underline" href="{{ url('login') }}">{{ __('Login') }}</a>
                        <a class="no-underline hover:underline" href="{{ url('register') }}">{{ __('Register') }}</a>
                    @else
                        @if (!Helper::auth_is_admin())
                            <div class="block space-x-4">
                                <span>{{ Auth::user()->name }}</span>
                                <a href="{{ url('logout') }}"
                                class="no-underline hover:underline"
                                onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ url('logout') }}" method="POST" class="hidden">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        @else
                            <div class="hidden sm:block space-x-4">
                                <span>{{ Auth::user()->name }}</span>
                                <a href="{{ url('logout') }}"
                                class="no-underline hover:underline"
                                onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ url('logout') }}" method="POST" class="hidden">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                            <div class="sm:hidden">
                                <span id="menu-toggle">
                                    <i class="fas fa-bars fa-2x"></i>
                                </span>
                            </div>
                        @endif
                    @endguest
                </nav>
            </div>
            @if(Auth::check())
                @include('dashboard.nav.navigation-mobile')
            @endif 
        </div>
    </header>
    <main>
        <div class="flex flex-col md:flex-row h-screen">
            @if(Auth::check() && Auth::user()->role != 'customer')
                @include('dashboard.nav.navigation')
            @endif           
            <div id="content" class="w-full p-3 py-24 lg:py-24">
                <div class="py-2">
                    @yield('content')
                </div>
            </div>            
        </div>
    </main>
    @if (request()->is('reports')||request()->is('orders')||request()->is('users')||request()->is('items'))
    <script src="{{ Helper::datepickerSrc() }}" defer></script>
    @endif
    <script src="{{ asset('js/scripts.js') }}" defer></script>
    <script src="{{ asset('selectize/js/selectize.min.js') }}" defer></script>
    <script src="{{ asset('selectize/js/selectize-helper.js') }}" defer></script>
    @yield('footer_script')
</body>
</html>