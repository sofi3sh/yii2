var DatatableResponsive = function() {

    //
    // Setup module components
    //

    var _componentDataTable = function() {
        $.extend( $.fn.dataTable.defaults, {
            bFilter: false,
            lengthChange: false,
            paging: false,
            autoWidth: false,
            responsive: true,
            stateSave: true,
            ordering: false,
            dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t>',
            drawCallback: function () {
                $('.datatable-basic').find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
            },
            preDrawCallback: function() {
                $('.datatable-basic').find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
            },
        });
        
        $('.datatable-responsive').DataTable().on( 'responsive-display', function () {
            $('.check').uniform();
        });
    };

    return {
        init: function() {
            _componentDataTable();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    DatatableResponsive.init();
});
