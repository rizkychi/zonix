export function init() {
    "use strict";

    if (forms) {
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener(
                "submit",
                function (event) {
                    // password confirm validation
                    confirm.setCustomValidity("");
                    if (password.value !== confirm.value) {
                        confirm.setCustomValidity("Passwords do not match");
                    }

                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add("was-validated");
                },
                false,
            );
        });
    }
}
