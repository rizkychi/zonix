@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toast.success("{{ session('success') }}");
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toast.error("{{ session('error') }}");
        });
    </script>
@endif

@if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toast.warning("{{ session('warning') }}");
        });
    </script>
@endif

@if(session('swal_custom_success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            swal.custom_success("{{ __('Well done !') }}", "{{ session('swal_custom_success') }}", "{{ __('Back') }}");
        });
    </script>
@endif

@if(session('swal_custom_error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            swal.custom_error("{{ __('Oops...! Something went Wrong ') }}", "{{ session('swal_custom_error') }}", "{{ __('Dismiss') }}");
        });
    </script>
@endif