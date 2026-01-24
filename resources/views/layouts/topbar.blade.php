<header class="app-header" id="appHeader">
    <div class="container-fluid w-100">
        <div class="d-flex align-items-center">

            {{-- IZQUIERDA --}}
            <div class="me-auto">
                <div class="d-inline-flex align-items-center gap-5">

                    {{-- LOGO --}}
                    <a href="/dashboard" class="fs-18 fw-semibold">
                        <img height="30" class="header-sidebar-logo-default d-none"
                             src="/images/RecusosNaturalesCompleto.svg">
                        <img height="30" class="header-sidebar-logo-light d-none"
                             src="/images/RecusosNaturalesCompleto.svg">
                        <img height="30" class="header-sidebar-logo-small d-none"
                             src="/images/RecursosNaturales.svg">
                        <img height="30" class="header-sidebar-logo-small-light d-none"
                             src="/images/RecursosNaturales.svg">
                    </a>

                    {{-- TOGGLE SIDEBAR --}}
                    <button type="button"
                            class="vertical-toggle btn btn-light-light text-muted icon-btn fs-5 rounded-pill"
                            id="toggleSidebar">
                        <i class="bi bi-arrow-bar-left header-icon"></i>
                    </button>

                    {{-- TOGGLE HORIZONTAL (no usado pero requerido) --}}
                    <button type="button"
                            class="horizontal-toggle btn btn-light-light text-muted icon-btn fs-5 rounded-pill d-none"
                            id="toggleHorizontal">
                        <i class="ri-menu-2-line header-icon"></i>
                    </button>

                </div>
            </div>

            {{-- DERECHA --}}
            <div class="flex-shrink-0 d-flex align-items-center gap-1">

                {{-- MODO CLARO / OSCURO --}}
                <div class="dark-mode-btn" id="toggleMode">

                    <button class="btn header-btn active" id="darkModeBtn">
                        <i class="bi bi-brightness-high"></i>
                    </button>
                    <button class="btn header-btn " id="lightModeBtn">
                        <i class="bi bi-moon-stars"></i>
                    </button>                    
                </div>

                {{-- PERFIL --}}
                <div class="dropdown pe-dropdown-mega d-none d-md-block">
                    <button class="header-profile-btn btn gap-1 text-start"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                        <span class="header-btn btn position-relative">
                            <img src="/assets/images/avatar/dummy_avatar.jpg"
                                 class="img-fluid rounded-circle">
                            <span class="position-absolute translate-middle badge border border-light rounded-circle bg-success">
                                <span class="visually-hidden">online</span>
                            </span>
                        </span>

                        <div class="d-none d-lg-block pe-2">
                            <span class="d-block mb-0 fs-13 fw-semibold">
                                {{ session('nombre') }} {{ session('apellido1') }}
                            </span>
                            <span class="d-block mb-0 fs-12 text-muted">
                                {{ session('rol') }}
                            </span>
                        </div>
                    </button>

                    <div class="dropdown-menu dropdown-mega-sm header-dropdown-menu p-3">

                        {{-- PERFIL HEADER --}}
                        <div class="border-bottom pb-2 mb-2 d-flex align-items-center gap-2">
                            <img src="/assets/images/avatar/dummy_avatar.jpg"
                                 class="rounded-circle header-profile-user"
                                 width="35" height="35">

                            <div>
                                <h6 class="mb-0 lh-base">
                                    {{ session('nombre') }} {{ session('apellido1') }}
                                </h6>
                                <p class="mb-0 fs-13 text-muted">
                                    Usuario del sistema
                                </p>
                            </div>
                        </div>

                        <ul class="list-unstyled mb-0">
                            <li>
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-1"></i>
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>

                    </div>
                </div>

            </div>
        </div>
    </div>
</header>
