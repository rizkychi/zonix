import Swal from "sweetalert2";

const confirmBtnClass = "btn btn-danger w-xs";
const cancelBtnClass = "btn btn-light w-xs me-2";
const primaryBtnClass = "btn btn-primary w-xs";

// ─── Toast Mixin ──────────────────────────────────────────
const ToastMixin = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: false,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});

// ─── Alert ────────────────────────────────────────────────
export const swal = {
    confirmBtnClass,
    cancelBtnClass,
    success: (title, text = "") =>
        Swal.fire({
            icon: "success",
            title,
            text,
            customClass: { confirmButton: primaryBtnClass },
            buttonsStyling: false,
        }),
    error: (title, text = "") =>
        Swal.fire({
            icon: "error",
            title,
            text,
            customClass: { confirmButton: primaryBtnClass },
            buttonsStyling: false,
        }),
    warning: (title, text = "") =>
        Swal.fire({
            icon: "warning",
            title,
            text,
            customClass: { confirmButton: primaryBtnClass },
            buttonsStyling: false,
        }),
    info: (title, text = "") =>
        Swal.fire({
            icon: "info",
            title,
            text,
            customClass: { confirmButton: primaryBtnClass },
            buttonsStyling: false,
        }),

    confirm: (title, text = "", opts = {}) =>
        Swal.fire({
            icon: "question",
            title,
            text,
            showCancelButton: true,
            confirmButtonText: opts.confirm ?? "Yes, proceed!",
            cancelButtonText: opts.cancel ?? "Cancel",
            customClass: {
                confirmButton: confirmBtnClass,
                cancelButton: cancelBtnClass,
            },
            buttonsStyling: false,
        }),
    custom_success: (title, text = "", cancelButtonText = "Back") =>
        Swal.fire({
             html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                '<div class="mt-4 pt-2 fs-15">' +
                '<h4>' + title + '</h4>' +
                '<p class="text-muted mx-4 mb-0">' + text + '</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            showConfirmButton: false,
            cancelButtonText: cancelButtonText,
            showCloseButton: true,
            customClass: { cancelButton: primaryBtnClass },
            buttonsStyling: false,
        }),
    custom_error: (title, text = "", cancelButtonText = "Dismiss") =>
        Swal.fire({
             html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon>' +
                '<div class="mt-4 pt-2 fs-15">' +
                '<h4>' + title + '</h4>' +
                '<p class="text-muted mx-4 mb-0">' + text + '</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            showConfirmButton: false,
            cancelButtonText: cancelButtonText,
            showCloseButton: true,
            customClass: { cancelButton: primaryBtnClass },
            buttonsStyling: false,
        }),
};

// ─── Toast ────────────────────────────────────────────────
export const toast = {
    success: (title) => ToastMixin.fire({ icon: "success", title }),
    error: (title) => ToastMixin.fire({ icon: "error", title }),
    warning: (title) => ToastMixin.fire({ icon: "warning", title }),
    info: (title) => ToastMixin.fire({ icon: "info", title }),
};

// ─── Auto Confirm (data-attribute handler) ────────────────
document.addEventListener("DOMContentLoaded", () => {
    document.addEventListener("click", async (e) => {
        const btn = e.target.closest("[data-confirm]");
        if (!btn) return;

        e.preventDefault();

        const title = btn.dataset.confirm ?? "Are you sure?";
        const text = btn.dataset.confirmText ?? "";
        const confirm = '<i class="ri-delete-bin-line me-1"></i>' + (btn.dataset.confirmOk ?? "Yes, delete it!");
        const cancel = btn.dataset.confirmCancel ?? "Cancel";

        const result = await Swal.fire({
            html:
                '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                '<div class="mt-4 pt-2 fs-15 mx-5">' +
                "<h4>" +
                title +
                "</h4>" +
                '<p class="text-muted mx-4 mb-0">' +
                text +
                "</p>" +
                "</div>" +
                "</div>",
            showCancelButton: true,
            confirmButtonText: confirm,
            cancelButtonText: cancel,
            customClass: {
                confirmButton: confirmBtnClass,
                cancelButton: cancelBtnClass,
            },
            buttonsStyling: false,
            reverseButtons: true
        });

        if (!result.isConfirmed) return;

        // if the button is inside a form, submit that form
        const form =
            btn.closest("form") ?? document.querySelector(btn.dataset.form);
        if (form) {
            form.submit();
            return;
        }

        // if the button has a data-form attribute, submit that form
        if (btn.dataset.form) {
            document.querySelector(btn.dataset.form)?.submit();
        }
    });
});
