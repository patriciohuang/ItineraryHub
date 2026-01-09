<?php
$pendingInvites = $pendingInvites ?? [];
$pendingSuggestions = $pendingSuggestions ?? [];
$totalNotifications = $totalNotifications ?? 0;
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="/">Itinerary Hub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="/">My Trips</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/trip/shared">Joining</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/trip/following">Following</a>
          </li>
          <li class="nav-item">
            <a class="nav-link position-relative" href="/notifications">
                <i class="bi bi-bell-fill"></i>
                <?php if ($totalNotifications > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= $totalNotifications ?>
                    </span>
                <?php endif; ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/logout">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="/login">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/register">Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>