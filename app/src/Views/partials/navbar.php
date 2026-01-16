<?php
$currentUri = $_SERVER['REQUEST_URI'] ?? '/';

function isActive($uri, $link) {
    if ($link === '/') {
        return $uri === '/' ? 'active' : '';
    }
    return str_starts_with($uri, $link) ? 'active' : '';
}

$pendingInvites = $pendingInvites ?? [];
$pendingSuggestions = $pendingSuggestions ?? [];
$totalNotifications = $totalNotifications ?? 0;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4" aria-label="Main Navigation">
  <div class="container">
    <a class="navbar-brand" href="/">Itinerary Hub</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['user_id'])): ?>
            
          <li class="nav-item">
            <a class="nav-link <?= isActive($currentUri, '/') ?>" 
               href="/"
               <?= isActive($currentUri, '/') ? 'aria-current="page"' : '' ?>>
               My Trips
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= isActive($currentUri, '/trip/shared') ?>" 
               href="/trip/shared"
               <?= isActive($currentUri, '/trip/shared') ? 'aria-current="page"' : '' ?>>
               Joining
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= isActive($currentUri, '/trip/following') ?>" 
               href="/trip/following"
               <?= isActive($currentUri, '/trip/following') ? 'aria-current="page"' : '' ?>>
               Following
            </a>
          </li>

          <li class="nav-item ms-lg-2">
            <a class="nav-link position-relative <?= isActive($currentUri, '/notifications') ?>" 
               href="/notifications" 
               aria-label="Notifications <?= $totalNotifications > 0 ? "($totalNotifications new)" : "" ?>"
               <?= isActive($currentUri, '/notifications') ? 'aria-current="page"' : '' ?>>
                
                <i class="bi bi-bell-fill" aria-hidden="true"></i>
                
                <?php if ($totalNotifications > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                        <?= $totalNotifications ?>
                        <span class="visually-hidden">unread notifications</span>
                    </span>
                <?php endif; ?>
            </a>
          </li>

          <li class="nav-item border-start ms-lg-3 ps-lg-3">
            <a class="nav-link" href="/logout">Logout</a>
          </li>

        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link <?= isActive($currentUri, '/login') ?>" href="/login">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= isActive($currentUri, '/register') ?>" href="/register">Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>