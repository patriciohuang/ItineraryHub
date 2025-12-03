<?php
use App\ViewModels\TripViewModel;

/** @var TripViewModel[] $trips */
?>

<?php require __DIR__ . '/../partials/header.php'; ?>
<?php require __DIR__ . '/../partials/navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Trips</h1>
            <?php require __DIR__ . '/../partials/messages.php'; ?>
            <a href="/add-trip">Add new trip</a>
            <?php if (empty($trips)): ?>
            <p>No trips available.</p>
            <?php else: ?>
            <ul>
                <?php foreach ($trips as $trip): ?>
                    <li><?php echo htmlspecialchars($trip->title); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>