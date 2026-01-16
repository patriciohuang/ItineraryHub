<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="addItemModalLabel">Add New Item</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form <?php if($isOwner):  ?>
                action="/trip/<?= $trip->id ?>/item/add" method="POST" enctype="multipart/form-data"
            <?php else: ?>
                action="/trip/<?= $trip->id ?>/item/suggest" method="POST" enctype="multipart/form-data"
            <?php endif; ?>>
                <div class="modal-body">
                    <?php if (isset($_SESSION['error_add_item'])): ?>
                        <?php require __DIR__ . '/../partials/messages.php'; ?>
                    <?php endif; ?>
                    <?php require __DIR__ . '/../partials/addItemForm.php'; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add to Itinerary</button>
                </div>
            </form>
        </div>
    </div>
</div>