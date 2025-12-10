<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4">Add a new trip</h2>
                    <?php require __DIR__ . '/../partials/messages.php'; ?>
                    <form action="/add-trip" method="POST">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Trip Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Summer in Tokyo">
                        </div>

                        <div>
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="e.g. A wonderful summer trip to Tokyo" rows="3"></textarea>
                        </div>

                        <div class="mb-3 d-flex gap-3">
                            <div>
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                            <div>
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                        </div>
                        <a href="/" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Trip</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>