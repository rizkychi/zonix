import { upperFirst } from "lodash";

let debounceTimer;

export function init() {
    $('select[name="source"]').on('change', function () {
        $('#filter-form').submit();
        window.showLoading(true);
    });

    $('select[name="target"]').on('change', function () {
        $('#filter-form').submit();
        window.showLoading(true);
    });

    $('select[name="status"]').on('change', function () {
        $('#filter-form').submit();
        window.showLoading(true);
    });

    $('#translations-table').on('keyup', 'textarea', function (e) {
        e.preventDefault();
        const key = $(this).data('key');
        const value = $(this).val();
        const locale = $(this).data('locale');
        const row = $(this).closest('tr');

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            $.ajax({
                url: '/admin/translations/save-row',
                method: 'POST',
                data: {
                    'locale': locale,
                    'key': key,
                    'value': value
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    toast.success(response.message);

                    // Status cell
                    const statusCell = row.find('td:nth-child(4)'); // Adjust the index based on table structure
                    // Update row status based on response
                    if (response.rowStatus === 'missing') {
                        statusCell.html('<span class="badge bg-danger-subtle text-danger">' + upperFirst(window.LaravelLang.missing) + '</span>');
                    } else if (response.rowStatus === 'identical') {
                        statusCell.html('<span class="badge bg-warning-subtle text-warning">' + upperFirst(window.LaravelLang.identical) + '</span>');
                    } else {
                        statusCell.html('<span class="badge bg-success-subtle text-success">' + upperFirst(window.LaravelLang.translated) + '</span>');
                    }
                },
                error: function (xhr) {
                    toast.error(window.LaravelLang.errorxhr);
                }
            });
        }, 1500);
    });
}