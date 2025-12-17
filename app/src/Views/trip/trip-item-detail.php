<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    
    <div class="mb-3">
        <a href="/trip/<?= $item->trip_id ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Itinerary
        </a>
    </div>

    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 text-primary"><?= htmlspecialchars($item->title) ?></h3>
                    <span class="badge bg-secondary"><?= htmlspecialchars($item->category_name) ?></span>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="text-muted text-uppercase small fw-bold fs-6">Schedule</h4>
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
                    </div>

                    <?php if (!empty($item->url)): ?>
                    <div class="mb-4">
                        <h4 class="text-muted text-uppercase small fw-bold fs-6">Link</h4>
                        <a href="<?= htmlspecialchars($item->url) ?>" target="_blank" class="text-decoration-none text-truncate d-block">
                            <i class="bi bi-link-45deg"></i> <?= htmlspecialchars($item->url) ?>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($item->notes)): ?>
                    <div>
                        <h4 class="text-muted text-uppercase small fw-bold fs-6">Notes</h4>
                        <div class="p-3 bg-light rounded border">
                            <?= nl2br(htmlspecialchars($item->notes)) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mt-4">
                         <a href="/trip/item/<?= $item->id ?>/edit" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i> Edit Details
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h3 class="mb-0"><i class="bi bi-paperclip"></i> Attachment</h3>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    
                    <?php if (empty($attachment)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-file-earmark-x fs-1"></i>
                            <p class="mt-2">No attachment found.</p>
                        </div>
                    
                    <?php else: ?>
                        <?php 
                            //$ext obtains the file extension of the attachment
                            $ext = strtolower(pathinfo($attachment->file_path, PATHINFO_EXTENSION));
                            // Determine if the attachment is an image based on its extension
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                        ?>

                        <div class="w-100">
                            <?php if ($isImage): ?>
                                <div class="ratio ratio-4x3 border rounded overflow-hidden position-relative shadow-sm" 
                                     style="cursor: pointer;"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#imagePreviewModal">
                                    
                                    <img src="<?= htmlspecialchars($attachment->file_path) ?>" 
                                         class="card-img-top object-fit-cover" 
                                         alt="Attachment">
                                         
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
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
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