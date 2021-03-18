$(function () {
    $('#data-table-responsive').DataTable({
        "order": [],
        "columnDefs": [ {
            "targets"  : 'no-sort',
            "orderable": false,
        }],
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.22/i18n/Dutch.json'
        }
    });
});
