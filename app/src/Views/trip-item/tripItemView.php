<ol class="list-group shadow-sm">
    <?php foreach ($items as $item): ?>
        <?php 
            $icon = 'bi-circle'; 
            $badgeClass = 'bg-secondary';
            
            $category = strtolower($item->category_name ?? 'activity');

            if (str_contains($category, 'flight')) {
                $icon = 'bi-airplane'; 
                $badgeClass = 'bg-primary';
            } elseif (str_contains($category, 'hotel')) {
                $icon = 'bi-house'; 
                $badgeClass = 'bg-warning text-dark';
            } elseif (str_contains($category, 'restaurant') || str_contains($category, 'food')) {
                $icon = 'bi-fork-knife'; 
                $badgeClass = 'bg-danger';
            } elseif (str_contains($category, 'activity')) {
                $icon = 'bi-ticket-perforated'; 
                $badgeClass = 'bg-success';
            } elseif (str_contains($category, 'car rental')) {
                $icon = 'bi-car-front-fill'; 
                $badgeClass = 'bg-info';
            } elseif (str_contains($category, 'train')) {
                $icon = 'bi-train-front'; 
                $badgeClass = 'bg-dark';
            }
            $participantList = [];
            if (!empty($item->participant_name)) {
                $participantList = explode(',', $item->participant_name);
            }
        ?>
        <li class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle border" style="width: 50px; height: 50px; flex-shrink: 0;">
                <i class="bi <?= $icon ?> fs-4 text-secondary"></i>
            </div>
            
            <div class="d-flex gap-2 w-100 justify-content-between">
                <div>
                    <h3 class="mb-1 fs-5">
                        <?php if($isOwner || $isParticipant): ?>
                        <a href="/trip/item/<?= $item->id ?>" class="text-decoration-none text-dark stretched-link">
                            <?= htmlspecialchars($item->title) ?>
                        </a>
                        <?php else: ?>
                            <?= htmlspecialchars($item->title) ?>
                        <?php endif; ?>
                        <span class="badge rounded-pill <?= $badgeClass ?> ms-2" style="font-size: 0.7em;">
                            <?= htmlspecialchars($item->category_name) ?>
                        </span>
                    </h3>
                    
                    <small class="text-muted">
                        <?php if(!empty($item->start_date)): ?>
                            <i class="bi bi-clock"></i> 
                            <?= date('d M Y, H:i', strtotime($item->start_date)) ?>
                        <?php endif; ?>
                        <?php if(!empty($item->end_date)): ?>
                            - <?= date('d M Y, H:i', strtotime($item->end_date)) ?>
                        <?php endif; ?>
                    </small>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-3 z-2">
                    <?php if (!empty($participantList)): ?>
                        <div class="d-flex align-items-center">
                            
                            <?php foreach($participantList as $index => $username): ?>
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center border border-2 border-white shadow-sm" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top"
                                    title="<?= htmlspecialchars($username) ?>"
                                    style="width: 30px; height: 30px; font-size: 11px;">
                                    <?= strtoupper(substr($username, 0, 2)) ?>
                                    </div>
                                <?php endforeach; ?>
                            
                            <span class="text-muted ms-2 small mx-2" style="font-size: 0.75rem;">
                                Going
                            </span>
                        </div>
                    <?php endif; ?>

                    <?php if ($isOwner): ?>
                    <div>
                        <button type="button" 
                                class="btn btn-outline-danger border-0 bg-white" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteTripModal"
                                data-bs-id="<?= $item->id ?>"
                                aria-label="Delete Item">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ol>

<?php require __DIR__ . '/../partials/deleteModal.php'; ?>
<script>
    const deleteModal = document.getElementById('deleteTripModal');
    deleteModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const tripItemId = button.getAttribute('data-bs-id');
        
        const form = deleteModal.querySelector('#deleteForm');
        form.action = `/trip/item/delete/${tripItemId}`;
    });
</script>