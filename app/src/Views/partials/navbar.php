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
          <li class="nav-item dropdown mx-2">
            <a class="nav-link position-relative" href="#" id="notifDropdown" data-bs-toggle="dropdown">
                <i class="bi bi-bell-fill"></i>
                <?php if (isset($pendingCount) && $pendingCount > 0): ?>
                    <span class="position-absolute top-20 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em;">
                        <?= $pendingCount ?>
                    </span>
                <?php endif; ?>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow" style="width: 300px;">
                <li><h6 class="dropdown-header">Invitations</h6></li>
                
                <?php if (!empty($pendingInvites)): ?>
                  <?php foreach($pendingInvites as $invite): ?>
                    <li class="border-top p-3 hover-bg-light">
                      <a href="/trip/join?trip_id=<?= $invite['trip_id'] ?>&role=<?= $invite['role_offered'] ?>" 
                        class="text-decoration-none text-dark d-block">
                          <p class="mb-1 small">
                            <strong><?= htmlspecialchars($invite['title']) ?></strong>
                          </p>
                          <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-info text-dark">
                              <?= strtolower($invite['role_offered']) ?>
                            </span>
                            <span class="text-primary small">
                              View Invite <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                      </a>
                    </li>
                  <?php endforeach; ?>
                <?php else: ?>
                    <li class="p-3 text-center text-muted small">No pending invitations.</li>
                <?php endif; ?>
            </ul>
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