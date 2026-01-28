
document.addEventListener('DOMContentLoaded', function () {
    const table = $('#default_datatable').DataTable();

    $('#firstNameLayout4').on('keyup', function () {
        table.search(this.value).draw();
    });
});

