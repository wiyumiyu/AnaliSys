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

<div class="pe-layout">
    @include('layouts.sidebar')

    <div class="pe-content">
        <main class="p-4">
            @yield('content')
        </main>
    </div>
</div>

@include('layouts.footer')

{{-- JS FABKIN --}}
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/libs/simplebar/simplebar.min.js"></script>
<script src="/libs/node-waves/waves.min.js"></script>
<script src="/js/app.js"></script>


{{-- Sidebar toggle (OBLIGATORIO) --}}
@include('layouts.partials.sidebar-toggle')



{{-- footer --}}
@include('layouts.partials.footer-js')

@yield('scripts')
</body>
</html>
