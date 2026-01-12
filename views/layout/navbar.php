<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">UAS App</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="catalog">Katalog</a></li>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li class="nav-item"><a class="nav-link text-warning" href="admin/dashboard">Admin Panel</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="logout">Keluar</a></li>
      </ul>
    </div>
  </div>
</nav>