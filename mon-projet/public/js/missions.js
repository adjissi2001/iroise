$(document).ready(function () {

    let table = $('#missionsTable').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json"
        }
    });

    // Recherche par colonne
    $('#missionsTable thead tr:eq(1) th').each(function (i) {
        $('input', this).on('keyup change clear', function () {
            if (table.column(i).search() !== this.value) {
                table.column(i).search(this.value).draw();
            }
        });
    });

});
