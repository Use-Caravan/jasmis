<script>
$(document).ready(function(){    
    $.extend( $.fn.dataTable.defaults, {
        'processiong' : true,
        'serverSide'  : true,        
        'searching': true, 
        "bSortCellsTop": true,       
        'bFilter':false,  
        //'dom': 't',
        "language": {
            "lengthMenu": '@lang("admincommon.table_length_menu")',
            "zeroRecords": '@lang("admincommon.table_nothing_found")',
            "info":  '@lang("admincommon.table_info")',
            "emptyTable": '@lang("admincommon.table_no_record_available")',
            "infoFiltered": '@lang("admincommon.table_filter")',
            "paginate": {
                "previous": '@lang("admincommon.table_previous")',
                "next": '@lang("admincommon.table_next")',
            }
        },
        "order": [[ 0, 'asc' ]],
        'bLengthChange' : false,
        "drawCallback": function(settings) {
            $('.selectpicker').selectpicker();
            popover();
        },           
        "initComplete": function(settings, json) {
            //return popover();
            //$('.selectpicker').selectpicker();
        },
    });    

    $('#dataTable').on('keyup','.filterText',function()
    {        
        var columnIndex = $(this).closest('th').index();
        window.dataTable.api().columns(columnIndex).search($(this).val()).draw();
    });    
    $('#dataTable').on('change','.filterSelect select', function() {
        /* console.log($(this).closest('tr').html());
        console.log($(this).closest('th').index());*/                
        var selected = $(this).val();        
        var columnIndex = $(this).closest('th').index();
        window.dataTable.api().columns(columnIndex).search($(this).val()).draw();
    });
    $('.filter_date_time_picker').datetimepicker({
        format: 'Y-M-D',
    }).on('dp.change', function (e) {
        //console.log($(this).val());
        var columnIndex = $(this).closest('th').index();
        window.dataTable.api().columns(columnIndex).search($(this).val()).draw();
    });

    setInterval(function() {
        window.dataTable.api().ajax.reload(null,false);
    },30000);
    
});
</script>