<?php
$successMessage = $_SESSION['success'] ?? ($success ?? null);

if ($successMessage): ?>
    <div class="alert auto-dismiss alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($successMessage) ?>
    </div>
    <?php 
    unset($_SESSION['success']); 
    ?>
<?php endif; ?>


<?php
$errorMessage = $_SESSION['error'] ?? ($error ?? null);

if ($errorMessage): ?>
    <div class="alert auto-dismiss alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($errorMessage) ?>
    </div>
    <?php 
    unset($_SESSION['error']); 
    ?>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var alerts = document.querySelectorAll('.alert.auto-dismiss');
        
        alerts.forEach(function(alert) {
            setTimeout(function() {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 2000);
        });
    });
</script>