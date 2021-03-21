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
        },
        rowCallback: function(row, data, index){
            if(data[6] < "0"){
                $(row).find('td:eq(6)').css('color', 'red');
            } else if(data[6] > "0.0") {
                $(row).find('td:eq(6)').css('color', 'green');
            }
        }
    });
});
