<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>

<script>
  $(document).ready(function() {
    $('#toggleFilter').click(function() {
      $('#filterBody').slideToggle(); // Toggle show/hide dengan animasi
    });
  });
</script>