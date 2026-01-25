<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Analisys Dashboard')</title>

    {{-- Aplicar tema antes del CSS --}}
    <script>
    (() => {
        const storedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-bs-theme', storedTheme);
    })();
    </script>

   
    
    {{-- CSS FABKIN --}}
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/bootstrap-icons.css" rel="stylesheet"> {{-- 👈 ESTA --}}
<link href="/css/icons.min.css" rel="stylesheet">
<link href="/css/app.min.css" rel="stylesheet">
<link href="/css/custom.css" rel="stylesheet">
<link href="/libs/simplebar/simplebar.min.css" rel="stylesheet">


    @yield('styles')
</head>

<body class="bg-body">
    
  @include('layouts.topbar')
    @include('layouts.sidebar')
    @include('partials.horizontal')    
    

     <main class="app-wrapper">
        <div class="container-fluid">

            @include('partials.page-title')

            @yield('content')
            @include('partials.switcher')
            @include('partials.scroll-to-top')
            @include('partials.footer')



            @yield('js')   
    
    
 
{{-- JS FABKIN --}}
<script src="/js/layout-setup.js"></script>
<script src="/libs/simplebar/simplebar.min.js"></script>

<script src="/js/app.js"></script>




</body>
</html>
