<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    <?php require __DIR__ . '/../partials/messages.php'; ?>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="card-title text-primary"><?= htmlspecialchars($trip->title) ?></h1>
                    <p class="text-muted">
                        <i class="bi bi-calendar3"></i> 
                        <?= date('M d, Y', strtotime($trip->start_date)) ?> 
                        &rarr; 
                        <?= date('M d, Y', strtotime($trip->end_date)) ?>
                    </p>
                    <p><?= htmlspecialchars($trip->description ?? '') ?></p>
                </div>
                <div>
                    <a href="/" class="btn btn-outline-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Itinerary</h3>
        <a class="btn btn-success" href="/trip/<?= $trip->id ?>/add-trip-item"> + Add Item</a>
    </div>

    <?php if (empty($items)): ?>
        <div class="alert alert-light border text-center py-5">
            <i class="bi bi-map fs-1 text-muted"></i>
            <p class="mt-3 text-muted">No items added yet. Start planning by clicking "Add Item"!</p>
        </div>
    <?php else: ?>
        <?php require __DIR__ . '/../trip/trip-item.php'; ?>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>