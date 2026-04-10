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
                console.log(response);
                var Sw = Swal.mixin({
                    text: response.message,
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                });
                if (response.is_active) {
                    Sw.fire({
                        icon: "success",
                    });
                } else {
                    Sw.fire({
                        icon: "error",
                    });
                }
                $toggle.prop("disabled", false); // Re-enable the toggle
            },
            error: function (xhr) {
                $toggle.prop("disabled", false); // Re-enable the toggle
                Swal.fire({
                    icon: "warning",
                    text: "Failed to update status. Please try again.",
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                });
            },
        });
    });
}
