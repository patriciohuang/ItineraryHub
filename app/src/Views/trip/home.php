<?php
/** @var \App\ViewModels\TripsViewModel $vm */
?>

<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    
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
                <div class="alert alert-info text-center">
                    <h2>No trips found!</h2>
                    <p>You haven't planned any trips yet. Click the button above to get started.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($vm->trips as $trip): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm d-flex">
                        <div class="card-body">
                            <h2 class="card-title text-primary mb-3 fs-3">
                                <?= htmlspecialchars($trip->title) ?>
                            </h2>
                            
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
                            <a href="/trip/<?= htmlspecialchars($trip->id) ?>" class="btn btn-outline-primary btn-sm w-100">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>