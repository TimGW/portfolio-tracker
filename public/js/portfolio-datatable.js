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
            const stringNumber = data[5].replace('%', '');
            const number = parseFloat(stringNumber);

            if(number < 0){
                $(row).find('td:eq(5)').css('color', 'red');
            } else {
                $(row).find('td:eq(5)').css('color', 'green');
            }
        }
    });
});
