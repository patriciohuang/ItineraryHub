<?php
/** @var \App\ViewModels\TripsViewModel $vm */
?>

<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Shared Trips</h1>
    </div>
    <?php require __DIR__ . '/../partials/messages.php'; ?>
    <?php if (empty($vm->trips)): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert text-center" role="alert">
                    <h2>No trips found!</h2>
                    <p>You don't have any shared trips yet.</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <ul class="row list-unstyled">
            <?php foreach ($vm->trips as $trip): ?>
                <li class="col-md-6 col-lg-4 mb-4">
                    <article class="card h-100 shadow-sm d-flex">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h2 class="card-title text-primary fs-3 m-0">
                                    <?= htmlspecialchars($trip->title) ?>
                                </h2>
                            </div> 
                            <p class="card-subtitle mb-2 text-muted">
                                <i class="bi bi-calendar3" aria-hidden="true"></i> 
                                <time datetime="<?= $trip->start_date ?>">
                                    <?= date('d M, Y', strtotime($trip->start_date)) ?>
                                </time> 
                                &rarr; 
                                <time datetime="<?= $trip->end_date ?>">
                                    <?= date('d M, Y', strtotime($trip->end_date)) ?>
                                </time>
                            </p>
                            <p class="card-text mt-3">
                                <?= htmlspecialchars($trip->description ?? 'No description provided.') ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0 pb-3">
                            <a href="/trip/<?= htmlspecialchars($trip->id) ?>?from=shared" class="btn btn-outline-primary btn-sm w-100" aria-label="View Details for <?= htmlspecialchars($trip->title) ?>">
                               View Details
                            </a>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>