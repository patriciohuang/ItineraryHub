<?php
/** @var \App\ViewModels\TripsViewModel $vm */
?>

<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Trips</h1>
        <a href="/trip/add" class="btn btn-primary">
            + Plan New Trip
        </a>
    </div>
    <?php require __DIR__ . '/../partials/messages.php'; ?>
    <div class="row">
        <?php if (empty($vm->trips)): ?>
            <div class="col-12">
                <div class="alert text-center">
                    <h2>No trips found!</h2>
                    <p>You haven't planned any trips yet. Click the button above to get started.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($vm->trips as $trip): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm d-flex">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h2 class="card-title text-primary fs-3 m-0">
                                    <?= htmlspecialchars($trip->title) ?>
                                </h2>
                                <button type="button" 
                                        class="btn btn-outline-danger btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteTripModal"
                                        data-bs-id="<?= $trip->id ?>"
                                        aria-label="Delete Trip">
                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                </button>
                            </div>
                            <p class="card-subtitle mb-2 text-muted">
                                <i class="bi bi-calendar3"></i> 
                                <?= date('d M, Y', strtotime($trip->start_date)) ?> 
                                &rarr; 
                                <?= date('d M, Y', strtotime($trip->end_date)) ?>
                            </p>
                            <p class="card-text mt-3">
                                <?= htmlspecialchars($trip->description ?? 'No description provided.') ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0 pb-3">
                            <a href="/trip/<?= htmlspecialchars($trip->id) ?>?from=home" class="btn btn-outline-primary btn-sm w-100">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
<?php require __DIR__ . '/../partials/delete-modal.php'; ?>
<script>
    const deleteModal = document.getElementById('deleteTripModal');
    deleteModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const tripId = button.getAttribute('data-bs-id');
        
        const form = deleteModal.querySelector('#deleteForm');
        form.action = `/trip/delete/${tripId}`;
    });
</script>
