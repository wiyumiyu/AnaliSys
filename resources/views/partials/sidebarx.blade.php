<aside class="pe-app-sidebar d-flex flex-column" id="sidebar">
    <div class="pe-app-sidebar-logo px-3 d-flex align-items-center position-relative">
        <!--begin::Brand Image-->
        <a href="/pages/dashboard.php" class="fs-18 fw-semibold">
            <img height="45" class="pe-app-sidebar-logo-default d-none" alt="Logo" src="/images/logo-light.svg">
            <img height="45" class="pe-app-sidebar-logo-light d-none" alt="Logo" src="/images/logo-dark.svg">
            <img height="45" class="pe-app-sidebar-logo-minimize d-none" alt="Logo" src="/images/logo-md-light.svg">
            <img height="45" class="pe-app-sidebar-logo-minimize-light d-none" alt="Logo" src="/images/logo-md.svg">
        </a>
        <!--end::Brand Image-->
    </div> 
    <nav class="pe-app-sidebar-menu nav nav-pills flex-grow-1" data-simplebar id="sidebar-simplebar">
        <ul class="pe-main-menu list-unstyled">


            @php
            use Illuminate\Support\Facades\Route;

            $ingresoDatosActive =
            (Route::has('textura.index') && request()->routeIs('textura.*'))
            || (Route::has('pa.index') && request()->routeIs('pa.*'))
            || (Route::has('densidad_aparente.index') && request()->routeIs('densidad_aparente.*'))
            || (Route::has('densidad_particulas.index') && request()->routeIs('densidad_particulas.*'))
            || (Route::has('porosidad.index') && request()->routeIs('porosidad.*'))
            || (Route::has('humedad_gravimetrica.index') && request()->routeIs('humedad_gravimetrica.*'))
            || (Route::has('conductividad_hidraulica.index') && request()->routeIs('conductividad_hidraulica.*'))
            || (Route::has('retencion.index') && request()->routeIs('retencion.*'))
            || (Route::has('curvatura.index') && request()->routeIs('curvatura.*'))
            || (Route::has('granulometria.index') && request()->routeIs('granulometria.*'))
            || (Route::has('estabilidad.index') && request()->routeIs('estabilidad.*'))
            || (Route::has('coel.index') && request()->routeIs('coel.*'));
            @endphp
            <li class="pe-slide pe-has-sub {{ $ingresoDatosActive ? 'active' : '' }}">
                <a href="#collapseAdvancedUI"
                   class="pe-nav-link {{ $ingresoDatosActive ? '' : 'collapsed' }}"
                   data-bs-toggle="collapse"
                   aria-expanded="{{ $ingresoDatosActive ? 'true' : 'false' }}"
                   aria-controls="collapseAdvancedUI">

                    <i class="bi bi-feather pe-nav-icon"></i>
                    <span class="pe-nav-content">Ingreso de Datos</span>
                    <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
                </a>

                <ul class="pe-slide-menu collapse {{ $ingresoDatosActive ? 'show' : '' }}"
                    id="collapseAdvancedUI">

                    <li class="slide pe-nav-content1">
                        <a href="javascript:void(0)">Ingreso de Datos</a>
                    </li>

                    {{-- TEXTURA (LARAVEL) --}}
                    <li class="pe-slide-item">
                        <a href="{{ route('textura.index') }}"
                           class="pe-nav-link {{ request()->routeIs('textura.*') ? 'active' : '' }}">
                            Textura
                        </a>
                    </li>

                    {{-- DENSIDAD APARENTE (LARAVEL) --}}
                    <li class="pe-slide-item">
                        <a href="{{ route('densidad_aparente.index') }}"
                            class="pe-nav-link {{ request()->routeIs('densidad_aparente.*') ? 'active' : '' }}">
                            Densidad Aparente
                        </a>
                    </li>

                    <li class="pe-slide-item">
                     <a href="{{ route('densidad_particulas.index') }}"
                            class="pe-nav-link {{ request()->routeIs('densidad_particulas.*') ? 'active' : '' }}">
                            Densidad de Particulas
                        </a>
                    </li>

                    <li class="pe-slide-item">
                        <a href="/pages/ingreso_datos/porosidad_total/listado.php" class="pe-nav-link">
                            Porosidad total
                        </a>
                    </li>

                    {{-- HUMEDAD GRAVIMETRICA (LARAVEL) --}}
                    <li class="pe-slide-item">
                        <a href="{{ route('humedad_gravimetrica.index') }}"
                            class="pe-nav-link {{ request()->routeIs('humedad_gravimetrica.*') ? 'active' : '' }}">
                            Humedad Gravimetrica
                        </a>
                    </li>

                    {{-- Conductividad Hidráulica (LARAVEL) --}}
                    <li class="pe-slide-item">
                        <a href="{{ route('conductividad_hidraulica.index') }}"
                            class="pe-nav-link {{ request()->routeIs('conductividad_hidraulica.*') ? 'active' : '' }}">
                            Conductividad Hidráulica
                        </a>
                    </li>

                    <li class="pe-slide-item">
                        <a href="/pages/ingreso_datos/retencion_humedad/listado.php" class="pe-nav-link">
                            Retención de humedad
                        </a>
                    </li>

                    <li class="pe-slide-item">
                        <a href="/pages/ingreso_datos/curvatura_retencion/listado.php" class="pe-nav-link">
                            Curvatura de Retención de Humedad
                        </a>
                    </li>

                    <li class="pe-slide-item">
                        <a href="/pages/ingreso_datos/granulometria_gruesa/listado.php" class="pe-nav-link">
                            Granulometría de la fracción gruesa
                        </a>
                    </li>

                    <li class="pe-slide-item">
                        <a href="/pages/ingreso_datos/estabilidad_agregados/listado.php" class="pe-nav-link">
                            Estabilidad de agregados
                        </a>
                    </li>

                    <li class="pe-slide-item">
                        <a href="/pages/ingreso_datos/coel/listado.php" class="pe-nav-link">
                            Coeficiente de extensibilidad lineal (COEL)
                        </a>
                    </li>

                    {{-- PERMEABILIDAD (LARAVEL) --}}
                    <li class="pe-slide-item">
                        <a href="{{ route('pa.index') }}"
                           class="pe-nav-link {{ request()->routeIs('pa.*') ? 'active' : '' }}">
                            Permeabilidad del aire
                        </a>
                    </li>

                </ul>
            </li>


            <li class="pe-slide pe-has-sub">
                <a href="#collapseInvoices" class="pe-nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapseInvoices">
                    <i class="bi bi-receipt pe-nav-icon"></i>
                    <span class="pe-nav-content">Reportes y Calidad</span>
                    <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
                </a>
                <ul class="pe-slide-menu collapse" id="collapseInvoices">
                    <li class="slide pe-nav-content1">
                        <a href="javascript:void(0)">Reportes y Gestión de Calidad</a>
                    </li>
                    <li class="pe-slide-item">
                        <a href="/pages/controles/listado.php" class="pe-nav-link">
                            Reporte de Controles
                        </a>
                    </li>
                    <li class="pe-slide-item">
                        <a href="/pages/resultados/listado.php" class="pe-nav-link">
                            Reporte de Resultados
                        </a>
                    </li>
                    <li class="pe-slide-item">
                        <a href="/pages/reporte/listado.php" class="pe-nav-link">
                            Reportes de clientes
                        </a>
                    </li>
                </ul>
            </li>


            @php
            $adminActive = request()->routeIs('usuarios.*')
            || request()->routeIs('bitacora.*');
            @endphp

            <li class="pe-slide pe-has-sub {{ $adminActive ? 'active' : '' }}">
                <a href="#collapseAuth"
                   class="pe-nav-link {{ $adminActive ? '' : 'collapsed' }}"
                   data-bs-toggle="collapse"
                   aria-expanded="{{ $adminActive ? 'true' : 'false' }}"
                   aria-controls="collapseAuth">

                    <i class="bi bi-person pe-nav-icon"></i>
                    <span class="pe-nav-content">Administración</span>
                    <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
                </a>

                <ul class="pe-slide-menu collapse {{ $adminActive ? 'show' : '' }}"
                    id="collapseAuth">

                    <li class="slide pe-nav-content1">
                        <a href="javascript:void(0)">Administración</a>
                    </li>

                    <li class="pe-slide-item">
                        <a href="{{ route('usuarios.index') }}"
                           class="pe-nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                            Usuario
                        </a>
                    </li>

                    <li class="pe-slide-item">
                        <a href="{{ route('bitacora.index') }}"
                           class="pe-nav-link {{ request()->routeIs('bitacora.*') ? 'active' : '' }}">
                            Bitácora
                        </a>
                    </li>

                </ul>
            </li>






        </ul>
    </nav>
</aside>
