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

<script src="/js/app.js"></script>


<script>
// ====== Sidebar: forzar funcionamiento del collapse ======
//document.addEventListener('DOMContentLoaded', function () {
//    const sidebar = document.getElementById('sidebar');
//    if (!sidebar || typeof bootstrap === 'undefined') return;
//
//    sidebar.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (trigger) {
//        trigger.addEventListener('click', function (e) {
//            e.preventDefault(); // ✅ corregido
//
//            const selector = this.getAttribute('href') || this.getAttribute('data-bs-target');
//            if (!selector) return;
//
//            const target = document.querySelector(selector);
//            if (!target) return;
//
//            const instance = bootstrap.Collapse.getOrCreateInstance(target, {
//                toggle: false // 👈 CLAVE
//            });
//
//            instance.toggle();
//        });
//    });
//});
</script>

<script>
// ====== Mantener activo el menú actual y abrir el padre ======
document.addEventListener("DOMContentLoaded", function () {
    const currentUrl = window.location.pathname;

    document.querySelectorAll(".pe-slide-item a.pe-nav-link").forEach(link => {
        const href = link.getAttribute("href");

        // Ignorar links inválidos
        if (!href || href === "javascript:void(0)") return;

        // Normalizar rutas (quitar archivo final)
        const hrefBase = href.replace(/\/[^\/]+\.php$/, '/');
        const currentBase = currentUrl.replace(/\/[^\/]+\.php$/, '/');

        /*
         Regla general:
         - Si la URL actual empieza con la ruta base del link,
           ese link pertenece a la sección activa
        */
        if (
            currentUrl === href ||
            currentBase.startsWith(hrefBase)
        ) {
            activarLink(link);
        }
    });

    function activarLink(link) {
        // 1️⃣ marcar link activo
        link.classList.add("active");

        // 2️⃣ abrir menú padre
        const parentMenu = link.closest(".pe-slide-menu");
        if (parentMenu) {
            parentMenu.classList.add("show");
        }

        // 3️⃣ marcar trigger padre como activo
        const parentTrigger = parentMenu?.previousElementSibling;
        if (parentTrigger && parentTrigger.classList.contains("pe-nav-link")) {
            parentTrigger.classList.add("active");
            parentTrigger.setAttribute("aria-expanded", "true");
        }
    }
});

</script>





@yield('scripts')
</body>
</html>
