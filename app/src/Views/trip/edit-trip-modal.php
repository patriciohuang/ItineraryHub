<div class="modal fade" id="editTripModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Trip Details</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="/trip/<?= $trip->id ?>" method="POST">
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Trip Title</label>
                        <input type="text" name="title" class="form-control" 
                               value="<?= htmlspecialchars($trip->title) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-control"><?= htmlspecialchars($trip->description ?? '') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" 
                                   value="<?= date('Y-m-d', strtotime($trip->start_date)) ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" 
                                   value="<?= date('Y-m-d', strtotime($trip->end_date)) ?>" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>