$(document).ready( function () {
    $('#students').DataTable({
        "pageLength": -1,
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
    });
});

