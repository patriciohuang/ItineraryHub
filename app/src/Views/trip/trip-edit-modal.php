<div class="modal fade" id="editTripModal" tabindex="-1" aria-labelledby="editTripLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="editTripLabel">Edit Trip Details</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/trip/<?= $trip->id ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">
                            Trip Title <span class="text-danger" aria-hidden="true">*</span>
                        </label>
                        <input type="text" name="title" id="edit_title" class="form-control" value="<?= htmlspecialchars($trip->title) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">
                            Description
                        </label>
                        <textarea id="edit_description" name="description" rows="3" class="form-control"><?= htmlspecialchars($trip->description ?? '') ?></textarea>
                    </div>
                    <fieldset class="row">
                        <legend class="col-form-label col-12 pt-0 fw-bold small text-uppercase text-muted">Duration</legend>
                        <div class="col-6 mb-3">
                            <label for="edit_start_date" class="form-label">
                                Start Date <span class="text-danger" aria-hidden="true">*</span>
                            </label>
                            <input type="date" name="start_date" id="edit_start_date" class="form-control" value="<?= date('Y-m-d', strtotime($trip->start_date)) ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="edit_end_date" class="form-label">
                                End Date <span class="text-danger" aria-hidden="true">*</span>
                            </label>
                            <input type="date" name="end_date" id="edit_end_date" class="form-control" value="<?= date('Y-m-d', strtotime($trip->end_date)) ?>" required>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>