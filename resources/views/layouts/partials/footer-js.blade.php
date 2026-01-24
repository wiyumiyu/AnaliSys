<script>
// ===============================
// TOGGLE SIDEBAR (COLLAPSE)
// ===============================
//document.addEventListener("DOMContentLoaded", function () {
//    const btn     = document.getElementById("toggleSidebar");
//    const sidebar = document.querySelector(".pe-app-sidebar");
//    const body    = document.body;
//
//    if (!btn || !sidebar) {
//        console.warn("Sidebar or toggle button not found.");
//        return;
//    }
//
//    btn.addEventListener("click", function () {
//
//        // 1️⃣ Colapsar / expandir sidebar
//        sidebar.classList.toggle("collapsed");
//
//        // 2️⃣ Marcar estado global
//        body.classList.toggle("sidebar-collapsed");
//
//        // 3️⃣ Cambiar ícono
//        const icon = this.querySelector("i");
//        if (icon) {
//            if (sidebar.classList.contains("collapsed")) {
//                icon.classList.remove("bi-arrow-bar-left");
//                icon.classList.add("bi-arrow-bar-right");
//            } else {
//                icon.classList.remove("bi-arrow-bar-right");
//                icon.classList.add("bi-arrow-bar-left");
//            }
//        }
//    });
//});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar   = document.getElementById('sidebar');
    const body      = document.body;

    if (!toggleBtn || !sidebar) return;

    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
        body.classList.toggle('sidebar-collapsed');
    });
});
</script>