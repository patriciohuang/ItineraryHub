<?php
$successMessage = $_SESSION['success'] ?? ($success ?? null);

if ($successMessage): ?>
    <div class="alert auto-dismiss alert-success alert-dismissible fade show" role="status">
        <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($successMessage) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>


<?php
$errorMessage = $_SESSION['error'] ?? ($error ?? null);

if ($errorMessage): ?>
    <div class="alert auto-dismiss alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($errorMessage) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var alerts = document.querySelectorAll('.alert.auto-dismiss');
        
        alerts.forEach(function(alert) {
            setTimeout(function() {
                if (alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    });
</script>