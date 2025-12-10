<div class="list-group">
    <?php foreach ($items as $item): ?>
        <div class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
        
            <div class="d-flex gap-2 w-100 justify-content-between">
                <div>
                    <h5 class="mb-0">
                        <?= htmlspecialchars($item->title) ?>
                    </h5>
                    <p class="mb-0 opacity-75">
                        <?= htmlspecialchars($item->description ?? '') ?>
                    </p>
                    <small class="text-muted">
                        <?= date('M d, H:i', strtotime($item->start_time)) ?>
                        <?php if($item->end_time): ?>
                            - <?= date('H:i', strtotime($item->end_time)) ?>
                        <?php endif; ?>
                    </small>
                </div>
                <div class="text-nowrap">
                    <a href="/trip/item/edit/<?= $item->id ?>" class="btn btn-sm btn-link text-decoration-none p-0">Edit</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>