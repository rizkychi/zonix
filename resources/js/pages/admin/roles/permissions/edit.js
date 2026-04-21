export function init() {
    // Handle select/deselect all checkboxes for each group
    $("[id^=select-all-]").on("change", function () {
        var isChecked = $(this).is(":checked");
        $(this)
            .closest(".list-group")
            .find("input[name='permissions[]']")
            .not(this)
            .prop("checked", isChecked);
    });

    // Handle global select/deselect all buttons
    $("#select-all-btn").on("click", function () {
        $("input[name='permissions[]']").prop("checked", true);
        $("[id^=select-all-]").prop("checked", true);
    });

    $("#deselect-all-btn").on("click", function () {
        $("input[name='permissions[]']").prop("checked", false);
        $("[id^=select-all-]").prop("checked", false);
    });

    // Update group select all checkbox state based on individual checkboxes
    $("input[name='permissions[]']").on("change", function () {
        var checkbox = $(this)
            .closest(".list-group")
            .not(".select-all")
            .find("input[name='permissions[]']");
        var allchecked = checkbox.length === checkbox.filter(":checked").length;

        $(this)
            .closest(".list-group")
            .find("[id^=select-all-]")
            .prop("checked", allchecked);
    });

    // Initialize group select all checkboxes on page load
    $("[id^=select-all-]").each(function () {
        var checkbox = $(this)
            .closest(".list-group")
            .not(".select-all")
            .find("input[name='permissions[]']");
        var allchecked = checkbox.length === checkbox.filter(":checked").length;

        $(this)
            .closest(".list-group")
            .find("[id^=select-all-]")
            .prop("checked", allchecked);
    });
}
