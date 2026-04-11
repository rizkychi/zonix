export function init() {
    // Toggle active status
    $("#resources-table").on("click", ".toggle-active", function () {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        var resourceId = $(this).data("id");
        var isActive = $(this).is(":checked");
        var $toggle = $(this);
        $toggle.prop("disabled", true); // Disable the toggle while processing

        $.ajax({
            url: "/admin/resources/" + resourceId + "/toggle",
            method: "PATCH",
            data: {
                _token: csrfToken,
                is_active: isActive,
            },
            success: function (response) {
                if (response.is_active) {
                    toast.success(response.message);
                } else {
                    toast.error(response.message);
                }
                $toggle.prop("disabled", false); // Re-enable the toggle
            },
            error: function (xhr) {
                $toggle.prop("disabled", false); // Re-enable the toggle
                toast.error('An error occurred while updating the resource status.');
            },
        });
    });
}
