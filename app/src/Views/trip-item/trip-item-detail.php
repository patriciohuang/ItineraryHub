<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main class="container mt-4">
    <?php if (!isset($_SESSION['error_edit_item'])): ?>
        <?php require __DIR__ . '/../partials/messages.php'; ?>
    <?php endif; ?>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="/trip/<?= $item->trip_id ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Itinerary
        </a>
        <? if ($isOwner): ?>
        <button type="button" 
                class="btn btn-outline-danger btn-sm" 
                data-bs-toggle="modal" 
                data-bs-target="#deleteTripModal"
                data-bs-id="<?= $item->id ?>">
            <i class="bi bi-trash"></i>
            DELETE ITEM
        </button>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-md-7 mb-4">
            <article class="card shadow-sm h-100" aria-labelledby="item-title">
                <header class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h1 class="mb-0 text-primary"><?= htmlspecialchars($item->title) ?></h1>
                    <span class="badge bg-secondary"><?= htmlspecialchars($item->category_name) ?></span>
                </header>
                <div class="card-body">
                <?php if (!empty($item->start_date) || !empty($item->end_date) || !empty($item->url) || !empty($item->notes)): ?>
                    <?php if (!empty($item->start_date)): ?>
                    <section class="mb-4" aria-label="Schedule">
                        <h2 class="text-muted text-uppercase small fw-bold fs-6">Schedule</h2>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-calendar-event fs-5 me-3 text-primary"></i>
                            <div>
                                <strong>Start:</strong> <?= date('l, M d, Y', strtotime($item->start_date)) ?> at <?= date('H:i', strtotime($item->start_date)) ?>
                            </div>
                        </div>
                        <?php if ($item->end_date): ?>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-flag fs-5 me-3 text-danger"></i>
                            <div>
                                <strong>End:</strong> <?= date('l, M d, Y', strtotime($item->end_date)) ?> at <?= date('H:i', strtotime($item->end_date)) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </section>
                    <?php endif; ?>

                    <?php if (!empty($item->url)): ?>
                    <section class="mb-4" aria-label="External Link">
                        <h2 class="text-muted text-uppercase small fw-bold fs-6">Link</h2>
                        <a href="<?= htmlspecialchars($item->url) ?>" target="_blank" class="text-decoration-none text-truncate d-block">
                            <i class="bi bi-link-45deg"></i> <?= htmlspecialchars($item->url) ?>
                        </a>
                    </section>
                    <?php endif; ?>

                    <?php if (!empty($item->notes)): ?>
                    <section class="mb-4" aria-label="Notes">
                        <h2 class="text-muted text-uppercase small fw-bold fs-6">Notes</h2>
                        <div class="p-3 bg-light rounded border">
                            <?= nl2br(htmlspecialchars($item->notes)) ?>
                        </div>
                    </section>
                    <?php endif; ?>
                
                <?php else: ?>
                    <div class="card-body d-flex align-items-center justify-content-center" style="height: 200px;">
                        <div class="text-center text-muted">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2">No details available for this item.</p>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($isOwner): ?>
                    <div class="mt-4">
                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editTripItemModal">
                            <i class="bi bi-pencil-square"></i> Edit Trip
                        </button>
                    </div>
                <?php endif; ?>
                </div>
            </article>
        </div>
        
        <div class="col-md-5 mb-4">
            <aside class="card shadow-sm h-100" aria-label="Attachment">
                <header class="card-header bg-white">
                    <h2 class="mb-0"><i class="bi bi-paperclip"></i> Attachment</h2>
                </header>
                <div class="card-body d-flex align-items-center justify-content-center">
                    
                    <?php if (empty($attachment)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-file-earmark-x fs-1"></i>
                            <p class="mt-2">No attachment found.</p>
                        </div>
                    
                    <?php else: ?>
                        <?php 
                            //$extention obtains the file extension of the attachment
                            $extention = strtolower(pathinfo($attachment->file_path, PATHINFO_EXTENSION));
                            // Determine if the attachment is an image based on its extension
                            $isImage = in_array($extention, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                        ?>

                        <div class="w-100">
                            <?php if ($isImage): ?>
                                <div class="ratio ratio-4x3 border rounded overflow-hidden position-relative shadow-sm" 
                                     style="cursor: pointer;"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#imagePreviewModal">
                                    
                                    <img src="<?= htmlspecialchars($attachment->file_path) ?>" 
                                         class="card-img-top object-fit-cover" 
                                         alt="Attachment for <?= htmlspecialchars($item->title) ?>">
                                         
                                    <div class="position-absolute top-50 start-50 translate-middle text-white opacity-0 hover-opacity-100 bg-dark bg-opacity-50 w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-arrows-fullscreen fs-2"></i>
                                    </div>
                                </div>
                                <div class="text-center mt-2 text-muted small">Click to enlarge</div>

                            <?php else: ?>
                                <div class="text-center p-4 border rounded bg-light">
                                    <i class="bi bi-file-earmark-pdf fs-1 text-danger mb-3"></i>
                                    <br>
                                    <a href="<?= htmlspecialchars($attachment->file_path) ?>" target="_blank" class="btn btn-outline-dark btn-sm">
                                        Open File
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </aside>
        </div>
    </div>
    <aside class="card shadow-sm mb-4">
        <header class="card-header bg-white">
            <h3 class="mb-0"><i class="bi bi-people"></i> Who's Going?</h3>
        </header>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 mb-3">
                <?php if (empty($participants)): ?>
                    <p class="text-muted small">No one assigned yet.</p>
                <?php else: ?>
                    <?php foreach ($participants as $participant): ?>
                        <div class="badge bg-light text-dark border p-2 d-flex align-items-center gap-2">
                            <i class="bi bi-person-circle text-secondary"></i>
                            <?= htmlspecialchars($participant->first_name ?: $participant->username) ?>
                            
                            <?php if ($isOwner): ?>
                                <form action="/trip/item/<?= $item->id ?>/participant/remove" method="POST">
                                    <input type="hidden" name="user_id" value="<?= $participant->id ?>">
                                    <?php if ($participant->id !== $currentUserId): ?>
                                    <button type="submit" class="btn-close btn-close ms-1" aria-label="Remove"></button>
                                    <?php endif; ?>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if ($isOwner): ?>
                <form action="/trip/item/<?= $item->id ?>/participant/add" method="POST" class="d-flex gap-2">
                    <select name="user_id" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Select a member...</option>
                        
                        <?php foreach ($allTripMembers as $member): ?>
                            <?php 
                                $isAlreadyAdded = false;
                                foreach($participants as $participant) {
                                    if($participant->id == $member->user_id) $isAlreadyAdded = true;
                                }
                            ?>
                            <?php if (!$isAlreadyAdded): ?>
                                <option value="<?= $member->user_id ?>">
                                    <?= htmlspecialchars($member->first_name ?: $member->username) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary">Add</button>
                </form>
            <?php endif; ?>

        </div>
    </aside>
</main>

<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="<?= htmlspecialchars($attachment->file_path) ?>" class="img-fluid" style="max-height: 85vh;" alt="Full Preview">
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <p class="text-muted small">Tap outside to close</p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
<?php require __DIR__ . '/../trip-item/trip-edit-item-modal.php'; ?>
<?php require __DIR__ . '/../partials/delete-modal.php'; ?>
<?php if (isset($_SESSION['error_edit_item'])): ?>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        var myModal = new bootstrap.Modal(document.getElementById('editTripItemModal'));
        myModal.show();
    });
</script>
<?php unset($_SESSION['error_edit_item']); ?>
<?php endif; ?>

<script>
    const deleteModal = document.getElementById('deleteTripModal');
    deleteModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const tripItemId = button.getAttribute('data-bs-id');
        
        const form = deleteModal.querySelector('#deleteForm');
        form.action = `/trip/item/delete/${tripItemId}`;
    });
</script>