document.addEventListener("DOMContentLoaded", () => {

    $('.datatable').each(function () {

        let ajax = $(this).data('ajax');
        let columns = $(this).data('columns');

        $(this).DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            scrollX: true,
            ajax: ajax,
            columns: columns
        });

    });

});