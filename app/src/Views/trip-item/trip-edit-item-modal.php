<div class="modal fade" id="editTripItemModal" tabindex="-1">
    <div class="modal-dialog"> 
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Item</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="/trip/item/<?= $item->id ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">

                    <?php if (isset($_SESSION['error_edit_item'])): ?>
                        <?php require __DIR__ . '/../partials/messages.php'; ?>
                    <?php endif; ?>

                    <?php require __DIR__ . '/../partials/edit-item-form.php'; ?>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add to Itinerary</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>