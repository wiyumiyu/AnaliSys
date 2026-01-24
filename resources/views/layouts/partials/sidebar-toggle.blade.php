<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const body = document.body;

    if (!toggleBtn || !sidebar) return;

    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
        body.classList.toggle('sidebar-collapsed');
    });
});
</script>