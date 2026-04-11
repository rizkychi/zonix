document.addEventListener("DOMContentLoaded", () => {
    $(".datatable").each(function () {
        let ajax = $(this).data("ajax");
        let columns = $(this).data("columns");
        let columnDefs = [];

        // Check first column configuration
        if (columns[0]) {
            if (columns[0].data === "DT_RowIndex") {
                columnDefs.push({
                    targets: 0,
                    className: "text-nowrap",
                    width: "1%",
                    render: function (data) {
                        return (
                            '<span style="padding:0 8px; display:inline-block;">' +
                            data +
                            "</span>"
                        );
                    },
                });
            }
        }
        if (columns[columns.length - 1]) {
            if (columns[columns.length - 1].data === "actions") {
                columnDefs.push({
                    targets: -1,
                    width: "1%",
                    render: function (data) {
                        return (
                            '<div class="hstack gap-1">' +
                            data +
                            "</div>"
                        );
                    },
                });
            }
        }

        $(this).DataTable({
            processing: true,
            serverSide: true,
            stateSave: true,
            scrollX: true,
            ajax: ajax,
            columns: columns,
            columnDefs: columnDefs,
        });
    });
});
