<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <h1 class="mb-4"><i class="bi bi-inbox"></i> Notifications</h1>

            <?php if (empty($pendingInvites) && empty($pendingSuggestions)): ?>
                <div class="text-center py-5 bg-light rounded border">
                    <i class="bi bi-check-circle fs-1 text-success" aria-hidden="true"></i>
                    <p class="mt-3 text-muted">You are all caught up! No new notifications.</p>
                    <a href="/" class="btn btn-primary mt-2">Go to My Trips</a>
                </div>
            <?php endif; ?>

            <?php if (!empty($pendingInvites)): ?>
                <section class="card mb-4 shadow-sm" aria-labelledby="invites-header">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0"><i class="bi bi-envelope-paper" aria-hidden="true"></i> Trip Invitations</h2>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($pendingInvites as $invite): ?>
                            <li class="list-group-item p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="mb-1"><?= htmlspecialchars($invite->title) ?></h3>
                                        <p class="mb-1 text-muted">
                                            You have been invited to join as a 
                                            <span class="badge bg-info text-dark"><?= strtolower($invite->role_offered) ?></span>
                                        </p>
                                    </div>
                                    <div>
                                        <a href="/trip/join?trip_id=<?= $invite->trip_id ?>&role=<?= $invite->role_offered ?>" 
                                           class="btn btn-sm btn-primary" aria-label="View invite for <?= htmlspecialchars($invite->title) ?>">
                                            View Invite
                                        </a>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>

            <?php if (!empty($pendingSuggestions)): ?>
                <section class="card mb-4 shadow-sm" aria-labelledby="suggestions-header">
                    <div class="card-header bg-success text-white">
                        <h2 class="mb-0" id="suggestions-header"><i class="bi bi-lightbulb" aria-hidden="true"></i> Item Suggestions</h2>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($pendingSuggestions as $item): ?>
                            <li class="list-group-item p-3">
                                <div class="d-flex w-100 justify-content-between">
                                    <h3 class="mb-1"><?= htmlspecialchars($item->title) ?></h3>
                                    <div class="text-end">
                                        <?php if(!empty($item->start_date)): ?>
                                        <small class="text-muted">
                                            <time datetime="<?= $item->start_date ?>"><?= date('M d, Y', strtotime($item->start_date)) ?></time>
                                        </small>
                                        <?php endif; ?>
                                        <?php if(!empty($item->end_date)): ?>
                                        <small class="text-muted">
                                            - <time datetime="<?= $item->end_date ?>"><?= date('M d, Y', strtotime($item->end_date)) ?></time>
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
                                        <button type="submit" class="btn btn-sm btn-success" aria-label="Accept suggestion: <?= htmlspecialchars($item->title) ?>">
                                            <i class="bi bi-check-lg"></i> Accept
                                        </button>
                                    </form>

                                    <form action="/trip/item/<?= $item->id ?>/process" method="POST">
                                        <input type="hidden" name="decision" value="reject">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Reject suggestion: <?= htmlspecialchars($item->title) ?>">
                                            <i class="bi bi-x-lg"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>