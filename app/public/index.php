<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Home';

$stats = [];
try {
    $stats['routes']   = oracle_select("SELECT COUNT(*) AS c FROM routes WHERE status='ACTIVE'")[0]['C'] ?? 0;
    $stats['vehicles'] = oracle_select("SELECT COUNT(*) AS c FROM vehicles WHERE status='ACTIVE'")[0]['C'] ?? 0;
    $stats['trips']    = oracle_select("SELECT COUNT(*) AS c FROM trips WHERE status='SCHEDULED'")[0]['C'] ?? 0;
} catch (Exception $e) {
    $dbError = $e->getMessage();
}

$announcements = [];
try {
    $announcements = mongo_find('announcements', ['active' => true], ['sort' => ['published_at' => -1], 'limit' => 3]);
} catch (Exception $e) {
    $mongoError = $e->getMessage();
}

require __DIR__ . '/header.php';
?>
<h1>SmartMove Transport Solutions</h1>
<p class="muted">Book bus, van and minibus trips across the island, manage fleet and driver operations, and track performance &mdash; all in one place.</p>

<?php if (!empty($dbError)): ?>
    <div class="flash flash-error">Could not reach Oracle: <?= h($dbError) ?> &mdash; check config.php and that the listener is running.</div>
<?php else: ?>
<div class="grid-2">
    <div class="card">
        <h2 style="margin-top:0">Active Routes</h2>
        <p style="font-size:2rem;margin:0"><?= (int)$stats['routes'] ?></p>
    </div>
    <div class="card">
        <h2 style="margin-top:0">Vehicles in Service</h2>
        <p style="font-size:2rem;margin:0"><?= (int)$stats['vehicles'] ?></p>
    </div>
</div>
<div class="card">
    <h2 style="margin-top:0">Upcoming Scheduled Trips</h2>
    <p style="font-size:2rem;margin:0"><?= (int)$stats['trips'] ?></p>
    <a class="btn" href="trips.php">Browse trips &amp; book a seat</a>
</div>
<?php endif; ?>

<h2>Latest Announcements</h2>
<?php if (!empty($mongoError)): ?>
    <div class="flash flash-error">Could not reach MongoDB: <?= h($mongoError) ?></div>
<?php elseif (empty($announcements)): ?>
    <p class="muted">No active announcements right now.</p>
<?php else: ?>
    <?php foreach ($announcements as $a): ?>
        <div class="card">
            <strong><?= h($a['title']) ?></strong>
            <span class="badge badge-warn"><?= h($a['category']) ?></span>
            <p><?= h($a['body']) ?></p>
        </div>
    <?php endforeach; ?>
    <a class="btn" href="announcements.php">View all announcements</a>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
