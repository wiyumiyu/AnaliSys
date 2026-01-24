<footer style="text-align:center; padding:10px; font-size:12px;">
    © {{ date('Y') }} Analisys
</footer>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebar");
    const body = document.body;

    if (!btn || !sidebar) return;

    btn.addEventListener("click", function () {
        sidebar.classList.toggle("collapsed");
        body.classList.toggle("sidebar-collapsed");
    });
});
</script>

