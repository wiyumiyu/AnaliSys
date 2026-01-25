<footer style="text-align:center; padding:10px; font-size:12px;">
    © {{ date('Y') }} Analisys
</footer>

<script>
// ===============================
// TOGGLE SIDEBAR (COLLAPSE)
// ===============================
document.addEventListener("DOMContentLoaded", function () {
    const btn      = document.getElementById("toggleSidebar");
    const sidebar  = document.querySelector(".pe-app-sidebar");
    const body     = document.body;

    if (!btn || !sidebar) {
        console.warn("Sidebar or toggle button not found.");
        return;
    }

    btn.addEventListener("click", function () {
        // 1) Colapsar / expandir sidebar
        sidebar.classList.toggle("collapsed");

        // 2) Marcar estado global en el body
        body.classList.toggle("sidebar-collapsed");

        // 3) Cambiar flecha
        const icon = this.querySelector("i");
        if (icon) {
            if (sidebar.classList.contains("collapsed")) {
                icon.classList.remove("bi-arrow-bar-left");
                icon.classList.add("bi-arrow-bar-right");
            } else {
                icon.classList.remove("bi-arrow-bar-right");
                icon.classList.add("bi-arrow-bar-left");
            }
        }
    });
});

</script>




<script>
// ===================================================
// MODO CLARO / OSCURO — COMPATIBLE CON TU TOPBAR REAL
// ===================================================

document.addEventListener("DOMContentLoaded", function () {

    const container = document.getElementById("toggleMode");
    const lightBtn  = document.getElementById("lightModeBtn");
    const darkBtn   = document.getElementById("darkModeBtn");

    if (!container || !lightBtn || !darkBtn) return;

    // 1. Aplicar tema guardado
    let saved = localStorage.getItem("theme") || "light";
    document.documentElement.setAttribute("data-bs-theme", saved);

    // Marcar el botón activo
    if (saved === "dark") {
        darkBtn.classList.add("active");
        lightBtn.classList.remove("active");
    } else {
        lightBtn.classList.add("active");
        darkBtn.classList.remove("active");
    }

    // 2. Activar modo claro
    lightBtn.addEventListener("click", function () {
        document.documentElement.setAttribute("data-bs-theme", "light");
        localStorage.setItem("theme", "light");

        lightBtn.classList.add("active");
        darkBtn.classList.remove("active");
    });

    // 3. Activar modo oscuro
    darkBtn.addEventListener("click", function () {
        document.documentElement.setAttribute("data-bs-theme", "dark");
        localStorage.setItem("theme", "dark");

        darkBtn.classList.add("active");
        lightBtn.classList.remove("active");
    });
});
</script>


