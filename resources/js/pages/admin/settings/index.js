export function init() {
    $('#enable_recaptcha').on('change', function() {
        if ($(this).is(':checked')) {
            $('#recaptcha_site_key').closest('.col-md-6').show('fast');
            $('#recaptcha_secret_key').closest('.col-md-6').show('fast');
        } else {
            $('#recaptcha_site_key').closest('.col-md-6').hide('fast');
            $('#recaptcha_secret_key').closest('.col-md-6').hide('fast');
        }
    });

    $('#enable_google_analytics').on('change', function() {
        if ($(this).is(':checked')) {
            $('#google_analytics_tracking_id').closest('.col-md-12').show('fast');
        } else {
            $('#google_analytics_tracking_id').closest('.col-md-12').hide('fast');
        }
    });
}