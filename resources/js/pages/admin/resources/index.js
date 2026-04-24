export function init() {
    // Toggle active status
    $("#resources-table").on("click", ".toggle-active", function () {
        var resourceId = $(this).data("id");
        var isActive = $(this).is(":checked");
        var $toggle = $(this);
        $toggle.prop("disabled", true); // Disable the toggle while processing

        $.ajax({
            url: "/admin/resources/" + resourceId + "/toggle",
            method: "PATCH",
            data: {
                is_active: isActive,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.is_active) {
                    toast.success(response.message);
                } else {
                    toast.error(response.message);
                }
            },
            error: function (xhr) {
                let response = xhr.responseJSON;
                toast.error(response.message);
            },
        }).always(function () {
            $toggle.prop("disabled", false); // Ensure toggle is re-enabled in case of any outcome
        });
    });
}
