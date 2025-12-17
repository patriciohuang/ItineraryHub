<div class="list-group shadow-sm">
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
            }
        ?>
        <div class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle border" style="width: 50px; height: 50px; flex-shrink: 0;">
                <i class="bi <?= $icon ?> fs-4 text-secondary"></i>
            </div>
            
            <div class="d-flex gap-2 w-100 justify-content-between">
                <div>
                    <h4 class="mb-1 fs-5">
                        <a href="/trip/item/<?= $item->id ?>" class="text-decoration-none text-dark stretched-link">
                            <?= htmlspecialchars($item->title) ?>
                        </a>
                        <span class="badge rounded-pill <?= $badgeClass ?> ms-2" style="font-size: 0.7em;">
                            <?= htmlspecialchars($item->category_name) ?>
                        </span>
                    </h4>
                    
                    <small class="text-muted">
                        <i class="bi bi-clock"></i> 
                        <?= date('M d, H:i', strtotime($item->start_date)) ?>
                        <?php if(!empty($item->end_date)): ?>
                            - <?= date('M d, H:i', strtotime($item->end_date)) ?>
                        <?php endif; ?>
                    </small>
                </div>
                
                <div class="text-end z-2 position-relative align-items-center d-flex gap-2">
                    <a href="/trip/item/<?= $item->id ?>/edit" class="btn btn-sm btn-outline-secondary border-0 hover-primary">
                        <i class="bi bi-pencil"></i>
                    </a>
                </div>
            </div>
        </div>      
    <?php endforeach; ?>
</div>