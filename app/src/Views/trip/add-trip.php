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
                            <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Summer in Tokyo" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Create Trip</button>
                        <a href="/" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>