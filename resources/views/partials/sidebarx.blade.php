
<aside class="pe-app-sidebar" id="sidebar">
    <div class="pe-app-sidebar-logo px-4 d-flex align-items-center position-relative">
        <!--begin::Brand Image-->
<a href="/pages/dashboard.php" class="fs-18 fw-semibold">
    <img height="45" class="pe-app-sidebar-logo-default d-none" alt="Logo" src="/images/RecusosNaturalesCompleto.svg">
    <img height="45" class="pe-app-sidebar-logo-light d-none" alt="Logo" src="/images/RecusosNaturalesCompleto.svg">
    <img height="45" class="pe-app-sidebar-logo-minimize d-none" alt="Logo" src="/images/RecursosNaturales.svg">
    <img height="45" class="pe-app-sidebar-logo-minimize-light d-none" alt="Logo" src="/images/RecursosNaturales.svg">
</a>
        <!--end::Brand Image-->
    </div> 
    <nav class="pe-app-sidebar-menu nav nav-pills" data-simplebar id="sidebar-simplebar">
        <ul class="pe-main-menu list-unstyled">

            <li class="pe-slide pe-has-sub">
                <a href="#collapseAdvancedUI" class="pe-nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapseAdvancedUI">
                    <i class="bi bi-feather pe-nav-icon"></i>
                    <span class="pe-nav-content">Ingreso de Datos</span>
                    <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
                </a>
                <ul class="pe-slide-menu collapse" id="collapseAdvancedUI">
                    <li class="slide pe-nav-content1">
                        <a href="javascript:void(0)">Ingreso de Datos</a>
                    </li>
                    <li class="pe-slide-item">
                        <a href="/pages/ingreso_datos/textura/listado.php" class="pe-nav-link">
                            Textura
                        </a>
                    </li>

                    <li class="pe-slide-item">
                        <a href="/pages/ingreso_datos/densidad_aparente/listado.php" class="pe-nav-link">
                            Densidad Aparente
                        </a>
                    </li>
                    <li class="pe-slide-item">
                        <a href="/pages/ingreso_datos/densidad_particulas/listado.php" class="pe-nav-link">
                            Densidad de Partículas
                        </a>
                    </li>
                    <li class="pe-slide-item">
                        <a href="/pages/ingreso_datos/porosidad_total/listado.php" class="pe-nav-link">
                            Porosidad total
                        </a>
                    </li>
                    <li class="pe-slide-item">
                        <a href="/pages/ingreso_datos/humedad_gravimetrica/listado.php" class="pe-nav-link">
                            Humedad Gravimétrica
                        </a>
                    </li>
                    <li class="pe-slide-item">
                        <a href="/pages/ingreso_datos/conductividad_hidraulica/listado.php" class="pe-nav-link">
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
                    <li class="pe-slide-item">
                        <a href="/pages/ingreso_datos/permeabilidad_aire/listado.php" class="pe-nav-link">
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

          
            <li class="pe-slide pe-has-sub">
                <a href="#collapseAuth" class="pe-nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapseAuth">
                    <i class="bi bi-person pe-nav-icon"></i>
                    <span class="pe-nav-content">Administración</span>
                    <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
                </a>
                <ul class="pe-slide-menu collapse" id="collapseAuth">
                    <li class="slide pe-nav-content1">
                        <a href="javascript:void(0)">Administración</a>
                    </li>
                    <li class="pe-slide-item">
                        <a href="usuarios" class="pe-nav-link">
                            Usuario
                        </a>
                    </li>
                    <li class="pe-slide-item">
                        <a href="/pages/bitacora/listado.php" class="pe-nav-link">
                            Bitácora
                        </a>
                    </li>
                </ul>
            </li>
      
        </ul>
    </nav>
</aside>
