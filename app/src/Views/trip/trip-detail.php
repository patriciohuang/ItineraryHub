<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>
<?php
switch ($origin ?? 'home') {
    case 'shared':
        $backLink = '/trip/shared';
        $backLabel = 'Back to Shared Trips';
        break;
    case 'collab':
        $backLink = '/trip/following';
        $backLabel = 'Back to Following';
        break;
    case 'home':
    default:
        $backLink = '/';
        $backLabel = 'Back to My Plans';
        break;
}
?>
<div class="container mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="<?= $backLink ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> <?= $backLabel ?>
        </a>

        <?php if ($isOwner): ?>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#shareModal">
            <i class="bi bi-share-fill"></i> Share Trip
        </button>
        <?php endif; ?>
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
                            <div class="badge bg-secondary">You are the Owner</div>
                        <?php elseif ($isParticipant): ?>
                            <div class="badge bg-secondary">You are a Participant</div>
                        <?php else: ?>
                            <div class="badge bg-secondary">Commenter View</div>
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
        <?php require __DIR__ . '/../trip-item/trip-item.php'; ?>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
<?php require __DIR__ . '/../trip-item/trip-add-item-modal.php'; ?>
<?php require __DIR__ . '/../trip/share-modal.php'; ?>

<?php if ($isOwner): ?>
    <?php require __DIR__ . '/../trip/trip-edit-modal.php'; ?>
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
