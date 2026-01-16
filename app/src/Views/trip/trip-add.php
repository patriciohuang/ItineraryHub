<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="card-title mb-4">Add a new trip</h1>
                    <?php require __DIR__ . '/../partials/messages.php'; ?>
                    <form action="/trip/add" method="POST">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Trip Title <span class="text-danger" aria-hidden="true">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Summer in Tokyo" required value="<?= htmlspecialchars($oldInput['title'] ?? '') ?>">
                        </div>

                        <div>
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="e.g. A wonderful summer trip to Tokyo" rows="3"><?= htmlspecialchars($oldInput['description'] ?? '') ?></textarea>
                        </div>

                        <fieldset class="row mb-4 mt-3">
                            <legend class="col-form-label col-12 pt-0 fw-bold small text-uppercase text-muted">Trip Duration</legend>
                            <div>
                                <label for="start_date" class="form-label">Start Date <span class="text-danger" aria-hidden="true">*</span></label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required value="<?= htmlspecialchars($oldInput['start_date'] ?? '') ?>">
                            </div>
                            <div>
                                <label for="end_date" class="form-label">End Date <span class="text-danger" aria-hidden="true">*</span></label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required value="<?= htmlspecialchars($oldInput['end_date'] ?? '') ?>">
                            </div>
                        </fieldset>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="/" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Trip</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>