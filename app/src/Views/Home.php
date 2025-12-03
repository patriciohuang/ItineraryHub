<?php
/** @var \App\Models\Trip[] $trip */
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h1>Trips</h1>
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
</body>
</html>