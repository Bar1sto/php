  </main>
  <footer class="page-footer blue lighten-1">
    <div class="container">
      <div class="center-align">
        © <?= date("Y") ?> Система документов
      </div>
    </div>
  </footer>

  <!-- JavaScript Materialize -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var selects = document.querySelectorAll('select');
      M.FormSelect.init(selects);
    });
  </script>
</body>
</html>
