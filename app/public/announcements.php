<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Announcements';

$announcements = [];
$error = null;
try {
    $announcements = mongo_find('announcements', ['active' => true], ['sort' => ['published_at' => -1]]);
} catch (Exception $e) {
    $error = $e->getMessage();
}

require __DIR__ . '/header.php';
?>
<h1>Travel Announcements</h1>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php elseif (empty($announcements)): ?>
    <p class="muted">No active announcements.</p>
<?php else: ?>
    <?php foreach ($announcements as $a): ?>
        <div class="card">
            <span class="badge badge-warn"><?= h($a['category']) ?></span>
            <h2 style="margin:0.4rem 0"><?= h($a['title']) ?></h2>
            <?php if (!empty($a['route_name'])): ?><p class="muted">Route: <?= h($a['route_name']) ?></p><?php endif; ?>
            <p><?= h($a['body']) ?></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
