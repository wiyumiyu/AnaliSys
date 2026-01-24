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


<script>
// ====== Sidebar: forzar funcionamiento del collapse ======
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar || typeof bootstrap === 'undefined') return;

    sidebar.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (trigger) {
        trigger.addEventListener('click', function (e) {
            e.preventDefault(); // ✅ corregido

            const selector = this.getAttribute('href') || this.getAttribute('data-bs-target');
            if (!selector) return;

            const target = document.querySelector(selector);
            if (!target) return;

            const instance = bootstrap.Collapse.getOrCreateInstance(target, {
                toggle: false // 👈 CLAVE
            });

            instance.toggle();
        });
    });
});
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
//document.addEventListener("DOMContentLoaded", function () {
//    const currentUrl = window.location.pathname;
//
//    // Seleccionar todos los links del menú
//    document.querySelectorAll(".pe-slide-item a.pe-nav-link").forEach(link => {
//        const href = link.getAttribute("href");
//
//        // Ignorar vacíos o javascript:void
//        if (!href || href === "javascript:void(0)") return;
//
//        // Si la URL actual contiene el href del link → este es el activo
//if (
//    currentUrl === href ||
//    (href.includes("/pages/ingreso_datos/textura") &&
//     currentUrl.includes("/pages/ingreso_datos/textura/")) ||
//    (href.includes("/pages/ingreso_datos/densidad_aparente") &&
//     currentUrl.includes("/pages/ingreso_datos/densidad_aparente/")) ||
//    (href.includes("/pages/ingreso_datos/densidad_particulas") &&
//     currentUrl.includes("/pages/ingreso_datos/densidad_particulas/")) ||
//    (href.includes("/pages/ingreso_datos/porosidad_total") &&
//     currentUrl.includes("/pages/ingreso_datos/porosidad_total/")) ||
//    (href.includes("/pages/ingreso_datos/conductividad_hidraulica") &&
//     currentUrl.includes("/pages/ingreso_datos/conductividad_hidraulica/"))||
//    (href.includes("/pages/ingreso_datos/humedad_gravimetrica") &&
//     currentUrl.includes("/pages/ingreso_datos/humedad_gravimetrica/")) || 
//    (href.includes("/pages/ingreso_datos/retencion_humedad") &&
//     currentUrl.includes("/pages/ingreso_datos/retencion_humedad/")) || 
//    (href.includes("/pages/ingreso_datos/curvatura_retencion") &&
//     currentUrl.includes("/pages/ingreso_datos/curvatura_retencion/")) || 
//    (href.includes("/pages/ingreso_datos/granulometria_gruesa") &&
//     currentUrl.includes("/pages/ingreso_datos/granulometria_gruesa/")) || 
//    (href.includes("/pages/ingreso_datos/estabilidad_agregados") &&
//     currentUrl.includes("/pages/ingreso_datos/estabilidad_agregados/"))  || 
//    (href.includes("/pages/ingreso_datos/coel") &&
//     currentUrl.includes("/pages/ingreso_datos/coel/")) || 
//    (href.includes("/pages/ingreso_datos/permeabilidad_aire") &&
//     currentUrl.includes("/pages/ingreso_datos/permeabilidad_aire/"))                
//) {
//
//            // 1. marcar hijo como activo
//            link.classList.add("active");
//
//            // 2. abrir menú padre
//            const parentMenu = link.closest(".pe-slide-menu");
//            if (parentMenu) {
//                parentMenu.classList.add("show"); // muestra el collapse
//            }
//
//            // 3. marcar el enlace del padre como activo y expandido
//            const parentTrigger = parentMenu?.previousElementSibling;
//            if (parentTrigger && parentTrigger.classList.contains("pe-nav-link")) {
//                parentTrigger.classList.add("active");
//                parentTrigger.setAttribute("aria-expanded", "true");
//            }
//        }
//    });
//});
</script>



<!--{{-- Sidebar toggle (OBLIGATORIO) --}}
@include('layouts.partials.sidebar-toggle')-->



<!--{{-- footer --}}
@include('layouts.partials.footer-js')-->

@yield('scripts')
</body>
</html>
