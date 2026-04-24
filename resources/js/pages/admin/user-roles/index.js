export function init() {
    // Popover initialization for role badges
    $(document).on("click", '[data-bs-toggle="popover"]', function () {
        $(this).popover("toggle");
    });

    const modal = document.getElementById("role-modal");
    const bsModal = bootstrap.Modal.getOrCreateInstance(modal);

    $("#users-table").on("click", ".btn-edit", function () {
        const rolesData = $(this).closest("tr").find(".roles-data").val();
        const rolesIds = rolesData ? rolesData.split(",") : [];

        // Reset the Choices.js selected items and set the new selected values
        const choices = document.querySelector("#roles");
        choices._choices.removeActiveItems();
        choices._choices.setChoiceByValue(rolesIds);

        // Store the user ID in a hidden input for later use
        const userId = $(this).data("id");
        $("#user-id").val(userId);

        // Display the username in the modal for better context
        const username = $(this).closest("tr").find(".username").text();
        $("#user-username").text(username[0].toUpperCase() + username.slice(1));

        // Show the modal
        bsModal.show();
    });

    $("#save-roles-btn").on("click", function () {
        const selectedRoles = $("#roles").val();
        const userId = $("#user-id").val();

        // Static Backdrop for the modal to prevent closing while processing
        bsModal._config.backdrop = "static";

        // Show the loading spinner and disable the save button
        $("#save-roles-btn .spinner-border").show();
        $("#save-roles-btn").prop("disabled", true);

        // Ajax request to save the selected roles for the user
        $.ajax({
            url: "/admin/user-roles/" + userId,
            method: "PATCH",
            data: {
                roles: selectedRoles,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                swal.success(response.title, response.message);
                bsModal.hide();

                // Refresh the DataTable to reflect changes
                $("#users-table").DataTable().ajax.reload();
            },
            error: function (xhr) {
                let response = xhr.responseJSON;
                swal.error(response.title, response.message);
            }
        }).always(function () {
            // Hide the loading spinner and enable the save button regardless of success or error
            $("#save-roles-btn .spinner-border").hide();
            $("#save-roles-btn").prop("disabled", false);

            // Reset the modal backdrop to default
            bsModal._config.backdrop = true;
        });
    });
}
