<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
        
        <button id="shareBtn" class="btn btn-outline-primary transition-all" onclick="copyTripLink()">
            <i class="bi bi-share"></i> Share Trip
        </button>
    </div>

    <div class="card shadow-sm mb-4 border-0 bg-light">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="display-6 fw-bold text-primary mb-2">
                        <?= htmlspecialchars($trip->title) ?>
                    </h1>

                    <div class="text-muted mb-3 d-flex align-items-center flex-wrap gap-3">
                        <div>
                            <i class="bi bi-calendar3"></i> 
                            <?= date('M d', strtotime($trip->start_date)) ?> - <?= date('M d, Y', strtotime($trip->end_date)) ?>
                        </div>

                        <div class="border-start ps-3">
                            <i class="bi bi-person-circle"></i> 
                            Planned by <strong><?= htmlspecialchars($trip->owner_name ?? 'Unknown') ?></strong>
                        </div>

                        <?php if ($isOwner): ?>
                            <div class="badge bg-success">You are the Owner</div>
                        <?php else: ?>
                            <div class="badge bg-secondary">Visitor View</div>
                        <?php endif; ?>
                    </div>

                    <p class="lead fs-6 mb-0"><?= htmlspecialchars($trip->description ?? '') ?></p>
                </div>

                <?php if ($isOwner): ?>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editTripModal">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Itinerary</h3>
        
        <?php if ($isOwner): ?>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addItemModal">
                <i class="bi bi-plus-lg"></i> Add Item
            </button>
        <?php else: ?>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addItemModal">
                <i class="bi bi-plus-lg"></i> Suggest Item
            </button>
        <?php endif; ?>
    </div>

    <?php if (!isset($_SESSION['error_add_item'])): ?>
        <?php require __DIR__ . '/../partials/messages.php'; ?>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="alert alert-light border text-center py-5">
            <i class="bi bi-map fs-1 text-muted"></i>
            <p class="mt-3 text-muted">
                <?= $isOwner ? 'Start planning by clicking "Add Item"!' : 'No items added to this itinerary yet.' ?>
            </p>
        </div>
    <?php else: ?>
        <?php require __DIR__ . '/../trip/trip-item.php'; ?>
    <?php endif; ?>

</div>

<script>
function copyTripLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const btn = document.getElementById('shareBtn');
        const originalContent = '<i class="bi bi-share"></i> Share Trip';

        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-success');
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Link Copied!';
        
        setTimeout(() => {
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-primary');
            btn.innerHTML = originalContent;
        }, 2000);
    });
}
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
<?php require __DIR__ . '/../trip/add-trip-item-modal.php'; ?>

<?php if ($isOwner): ?>
    <?php require __DIR__ . '/../trip/edit-trip-modal.php'; ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_add_item'])): ?>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            var myModal = new bootstrap.Modal(document.getElementById('addItemModal'));
            myModal.show();
        });
    </script>
    <?php unset($_SESSION['error_add_item']); ?>
<?php endif; ?>
