(function () {
    "use strict";
    window.addEventListener(
        "load",
        function () {
            // Set custom validity from server errors
            document.querySelectorAll(".is-invalid").forEach(function (input) {
                var serverError = input.parentElement.querySelector(
                    ".invalid-feedback.server-error",
                );
                input.setCustomValidity(
                    serverError ? serverError.textContent.trim() : "Invalid",
                );
            });

            var forms = document.getElementsByClassName("needs-validation");
            Array.prototype.filter.call(forms, function (form) {
                form.addEventListener(
                    "submit",
                    function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                            form.querySelectorAll(":invalid").forEach(
                                function (input) {
                                    showError(input, input.validationMessage);
                                },
                            );
                        }
                        form.classList.add("was-validated");
                    },
                    false,
                );

                form.querySelectorAll("input, select, textarea").forEach(
                    function (input) {
                        input.addEventListener("input", function () {
                            // Clear custom validity so checkValidity() not blocked
                            this.setCustomValidity("");

                            // Clear server error
                            var serverError = this.parentElement.querySelector(
                                ".invalid-feedback.server-error",
                            );
                            if (serverError) {
                                serverError.classList.remove("d-block");
                                serverError.style.display = "none";
                            }

                            if (this.validity.valid) {
                                clearError(this);
                            } else {
                                showError(this, this.validationMessage);
                            }

                            checkFormState(form);
                        });
                    },
                );
            });
        },
        false,
    );

    function checkFormState(form) {
        var anyTouched = form.querySelector(".is-invalid, .is-valid");
        if (anyTouched || form.querySelector('input:not([value=""])')) {
            form.classList.add("was-validated");
        }
    }

    function showError(input, message) {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");

        // if server error exists and visible, do not override with js error
        var serverError = input.parentElement.querySelector(
            ".invalid-feedback.server-error",
        );
        if (
            serverError &&
            serverError.style.display !== "none" &&
            serverError.classList.contains("d-block")
        ) {
            return;
        }

        var feedback = input.parentElement.querySelector(
            ".invalid-feedback.js-error",
        );
        if (!feedback) {
            feedback = document.createElement("div");
            feedback.className = "invalid-feedback js-error";
            var validFeedback =
                input.parentElement.querySelector(".valid-feedback");
            if (validFeedback) {
                validFeedback.after(feedback);
            } else {
                input.after(feedback);
            }
        }
        feedback.textContent = message;
        feedback.style.display = "block";
    }

    function clearError(input) {
        input.classList.remove("is-invalid");
        input.classList.add("is-valid");

        // Clear js error
        var jsError = input.parentElement.querySelector(
            ".invalid-feedback.js-error",
        );
        if (jsError) jsError.style.display = "none";

        // Clear server error
        var serverError = input.parentElement.querySelector(
            ".invalid-feedback.server-error",
        );
        if (serverError) {
            serverError.classList.remove("d-block");
            serverError.style.display = "none";
        }
    }
})();
