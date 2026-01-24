<script>
// ====== Mantener activo el menú actual y abrir el padre ======
document.addEventListener("DOMContentLoaded", function () {
    if (document.body.classList.contains('sidebar-collapsed')) return;

    const currentUrl = window.location.pathname;

    document.querySelectorAll(".pe-slide-item a.pe-nav-link").forEach(link => {
        const href = link.getAttribute("href");
        if (!href) return;

        if (currentUrl.startsWith(href)) {
            link.classList.add("active");
            const parentMenu = link.closest(".pe-slide-menu");
            if (parentMenu) parentMenu.style.display = 'block';
        }
    });
});

function activarLink(link) {

    // 👉 SI el sidebar está colapsado → NO abrir submenús
    if (body.classList.contains('sidebar-collapsed')) {
        link.classList.add("active");
        return;
    }

    // Sidebar normal (expandido)
    link.classList.add("active");

    const parentMenu = link.closest(".pe-slide-menu");
    if (parentMenu) {
        parentMenu.classList.add("show");
    }

    const parentTrigger = parentMenu?.previousElementSibling;
    if (parentTrigger && parentTrigger.classList.contains("pe-nav-link")) {
        parentTrigger.classList.add("active");
        parentTrigger.setAttribute("aria-expanded", "true");
    }
}

});
</script>
