<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>
<main class="container mt-5">
    <div class="container mt-4">
        <a href="/" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <section class="card shadow-sm border-0">
                <div class="card-body text-center p-5">
                    
                    <h1 class="text-primary mb-3">Trip Invitation</h1>
                    
                    <p class="lead mb-4">
                        You have been invited to join <strong><?= htmlspecialchars($trip->title) ?></strong> 
                        as a <strong><?= htmlspecialchars($roleOffered) ?></strong>.
                    </p>

                    <div class="bg-light border rounded p-3 mb-4 d-inline-block">
                        <i class="bi bi-calendar3 text-primary me-2" aria-hidden="true"></i> 
                        <time datetime="<?= $trip->start_date ?>">
                            <?= date('M d', strtotime($trip->start_date)) ?>
                        </time>
                        <span class="mx-1">-</span>
                        <time datetime="<?= $trip->end_date ?>">
                            <?= date('M d, Y', strtotime($trip->end_date)) ?>
                        </time>
                    </div>

                    <p id="decision-prompt" class="text-muted small mb-4">
                        Do you want to accept this role?
                    </p>

                    <form action="/trip/join/confirm" method="POST" aria-labelledby="decision-prompt">
                        <input type="hidden" name="trip_id" value="<?= htmlspecialchars($trip->id) ?>">
                        <input type="hidden" name="role" value="<?= htmlspecialchars($roleOffered) ?>">
                        <input type="hidden" name="sig" value="<?= htmlspecialchars($signature) ?>">
                        
                        <div class="d-grid gap-2 d-md-block">
                            <button type="submit" name="decision" value="accept" class="btn btn-primary px-4 m-2">
                                Yes, Join Trip
                            </button>
                            <button type="submit" name="decision" value="reject" class="btn btn-outline-secondary px-4 m-2">
                                No, Reject
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>