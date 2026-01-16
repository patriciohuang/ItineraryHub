<div class="modal fade" id="editTripItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="editItemModalLabel">Edit Item</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/trip/item/<?= $item->id ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <?php if (isset($_SESSION['error_edit_item'])): ?>
                        <?php require __DIR__ . '/../partials/messages.php'; ?>
                    <?php endif; ?>
                    <?php require __DIR__ . '/../partials/edit-item-form.php'; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>