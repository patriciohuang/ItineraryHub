<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4>Add Item to Itinerary</h4>
                </div>
                <div class="card-body">
                    
                    <?php require __DIR__ . '/../partials/messages.php'; ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label for="category" class="form-label">Type of Activity</label>
                            <select name="category_id" id="category" class="form-select" required>
                                <option value="" disabled <?= empty($oldInput['category_id']) ? 'selected' : '' ?>>
                                    Choose a category...
                                </option>
                                
                                <?php foreach ($categories as $category): ?>
                                    <?php 
                                        $isSelected = isset($oldInput['category_id']) && $oldInput['category_id'] == $category->id; 
                                    ?>
                                    <option value="<?= $category->id ?>" <?= $isSelected ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Title (e.g., Flight to Paris, Hilton Hotel)</label>
                            <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($oldInput['title'] ?? '') ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Time</label>
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="<?= htmlspecialchars($oldInput['start_date'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Time</label>
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="<?= htmlspecialchars($oldInput['end_date'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="url" class="form-label">Booking Link / Location URL (Optional)</label>
                                <input type="url" name="url" id="url" class="form-control" placeholder="https://..." value="<?= htmlspecialchars($oldInput['url'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="attachment" class="form-label">Attach File (Image/PDF)</label>
                                <input type="file" name="attachment" id="attachment" class="form-control">
                                <div class="form-text">Optional. Upload a ticket, reservation confirmation, or photo.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Reservation numbers, gate info, etc."></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/trip/<?= $tripId ?>" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add to Itinerary</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>