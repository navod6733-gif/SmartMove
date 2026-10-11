<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Routes';

$routes = [];
$error = null;
try {
    $routes = oracle_select("SELECT route_id, route_name, origin, destination, distance_km, estimated_duration_min, status FROM routes ORDER BY route_name");
    foreach ($routes as &$r) {
        $r['stops'] = oracle_select(
            "SELECT stop_name, stop_order, arrival_offset_min FROM route_stops WHERE route_id = :rid ORDER BY stop_order",
            [':rid' => $r['ROUTE_ID']]
        );
    }
    unset($r);
} catch (Exception $e) {
    $error = $e->getMessage();
}

require __DIR__ . '/header.php';
?>
<h1>Routes</h1>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php else: ?>
    <?php foreach ($routes as $r): ?>
        <div class="card">
            <h2 style="margin-top:0"><?= h($r['ROUTE_NAME']) ?>
                <?php if ($r['STATUS'] !== 'ACTIVE'): ?><span class="badge badge-bad">Suspended</span><?php endif; ?>
            </h2>
            <p class="muted"><?= h($r['ORIGIN']) ?> &rarr; <?= h($r['DESTINATION']) ?>
               &middot; <?= h($r['DISTANCE_KM']) ?> km &middot; ~<?= h($r['ESTIMATED_DURATION_MIN']) ?> min</p>
            <p><strong>Stops:</strong>
                <?= implode(' &rarr; ', array_map(fn($s) => h($s['STOP_NAME']), $r['stops'])) ?>
            </p>
            <a class="btn" href="trips.php?route_id=<?= (int)$r['ROUTE_ID'] ?>">See trips on this route</a>
            <a class="btn" style="background:#6b7573" href="reviews.php?route_id=<?= (int)$r['ROUTE_ID'] ?>">Read reviews</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
