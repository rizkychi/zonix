/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Form validation Js File
*/

// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
	'use strict';
	window.addEventListener('load', function () {
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.getElementsByClassName('needs-validation');
		const password = document.getElementById('userpassword');
		const confirm = document.getElementById('input-password');
		// Loop over them and prevent submission
		if (forms)
			var validation = Array.prototype.filter.call(forms, function (form) {
				form.addEventListener('submit', function (event) {
					// password confirm validation
					confirm.setCustomValidity('');
					if (password.value !== confirm.value) {
						confirm.setCustomValidity('Passwords do not match');
					}

					if (form.checkValidity() === false) {
						event.preventDefault();
						event.stopPropagation();
					}
					form.classList.add('was-validated');
				}, false);
			});
	}, false);
})();