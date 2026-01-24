<aside class="pe-app-sidebar" id="sidebar">

    {{-- LOGO --}}
    <div class="pe-app-sidebar-logo px-6 d-flex align-items-center">
        <a href="/dashboard">
            <img height="72" src="/assets/images/logo/logorn.png" alt="Logo">
        </a>
    </div>

    <nav class="pe-app-sidebar-menu" data-simplebar>
        <ul class="pe-main-menu list-unstyled">

            {{-- INGRESO DE DATOS --}}
            <li class="pe-slide pe-has-sub">
                <a href="javascript:void(0)" class="pe-nav-link">
                    <i class="bi bi-feather pe-nav-icon"></i>
                    <span class="pe-nav-content">Ingreso de Datos</span>
                    <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
                </a>

                <ul class="pe-slide-menu">
                    <li class="slide pe-nav-content1">Ingreso de Datos</li>

                    <li class="pe-slide-item"><a href="/ingreso-datos/textura">Textura</a></li>
                    <li class="pe-slide-item"><a href="/ingreso-datos/densidad-aparente">Densidad Aparente</a></li>
                    <li class="pe-slide-item"><a href="/ingreso-datos/densidad-particulas">Densidad de Partículas</a></li>
                    <li class="pe-slide-item"><a href="/ingreso-datos/porosidad-total">Porosidad total</a></li>
                </ul>
            </li>

            {{-- ADMIN --}}
            @if(session('rol') === 'ADMIN')
            <li class="pe-slide pe-has-sub">
                <a href="javascript:void(0)" class="pe-nav-link">
                    <i class="bi bi-person pe-nav-icon"></i>
                    <span class="pe-nav-content">Administración</span>
                    <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
                </a>

                <ul class="pe-slide-menu">
                    <li class="slide pe-nav-content1">Administración</li>
                    <li class="pe-slide-item"><a href="/usuarios">Usuarios</a></li>
                    <li class="pe-slide-item"><a href="/bitacora">Bitácora</a></li>
                </ul>
            </li>
            @endif

        </ul>
    </nav>
</aside>
