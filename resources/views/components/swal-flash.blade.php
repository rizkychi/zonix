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