<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <h2 class="mb-4"><i class="bi bi-inbox"></i> Notifications</h2>

            <?php if (empty($pendingInvites) && empty($pendingSuggestions)): ?>
                <div class="text-center py-5 bg-light rounded border">
                    <i class="bi bi-check-circle fs-1 text-success"></i>
                    <p class="mt-3 text-muted">You are all caught up! No new notifications.</p>
                    <a href="/" class="btn btn-primary mt-2">Go to My Trips</a>
                </div>
            <?php endif; ?>

            <?php if (!empty($pendingInvites)): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-envelope-paper"></i> Trip Invitations</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($pendingInvites as $invite): ?>
                            <div class="list-group-item p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1"><?= htmlspecialchars($invite['title']) ?></h5>
                                        <p class="mb-1 text-muted">
                                            You have been invited to join as a 
                                            <span class="badge bg-info text-dark"><?= strtolower($invite['role_offered']) ?></span>
                                        </p>
                                    </div>
                                    <div>
                                        <a href="/trip/join?trip_id=<?= $invite['trip_id'] ?>&role=<?= $invite['role_offered'] ?>" 
                                           class="btn btn-sm btn-primary">
                                            View Invite
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($pendingSuggestions)): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-lightbulb"></i> Item Suggestions</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($pendingSuggestions as $item): ?>
                            <div class="list-group-item p-3">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><?= htmlspecialchars($item->title) ?></h5>
                                    <div class="text-end">
                                        <?php if(!empty($item->start_date)): ?>
                                        <small class="text-muted">
                                            <?= date('M d, Y', strtotime($item->start_date)) ?>
                                        </small>
                                        <?php endif; ?>
                                        <?php if(!empty($item->end_date)): ?>
                                        <small class="text-muted">
                                            - <?= date('M d, Y', strtotime($item->end_date)) ?>
                                        </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="mb-1">
                                    Suggested by <strong><?= htmlspecialchars($item->suggester_name ?? 'A member') ?></strong>
                                    in <em><?= htmlspecialchars($item->trip_title ?? 'Unknown Trip') ?></em>
                                </p>
                                <div class="d-flex gap-2 mt-2">
                                    <form action="/trip/item/<?= $item->id ?>/process" method="POST">
                                        <input type="hidden" name="decision" value="approve">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-lg"></i> Accept
                                        </button>
                                    </form>

                                    <form action="/trip/item/<?= $item->id ?>/process" method="POST">
                                        <input type="hidden" name="decision" value="reject">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-lg"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>