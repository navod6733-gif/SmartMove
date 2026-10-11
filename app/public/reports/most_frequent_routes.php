<?php
require_once __DIR__ . '/../../includes/functions.php';
$pageTitle = 'Most Frequent Routes';
require_admin();

$rows = [];
$error = null;
try {
    $rows = call_report('rpt_most_frequent_routes');
} catch (Exception $e) {
    $error = $e->getMessage();
}

require __DIR__ . '/../header.php';
?>
<h1>Report: Most Frequently Used Routes</h1>
<p class="muted">Ranked by tickets sold, based on <code>rpt_most_frequent_routes</code>.</p>

<div class="card" style="padding:0.5rem 1rem">
    <a href="most_frequent_routes.php">Most frequent routes</a> &middot;
    <a href="revenue.php">Revenue by period</a> &middot;
    <a href="passenger_history.php">Passenger history</a> &middot;
    <a href="maintenance_due.php">Maintenance due</a> &middot;
    <a href="top_rated.php">Top rated</a>
</div>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php elseif (empty($rows)): ?>
    <p class="muted">No data yet.</p>
<?php else: ?>
    <table>
        <tr><th>Route</th><th>Origin &rarr; Destination</th><th>Tickets sold</th><th>Trips run</th><th>Avg tickets/trip</th></tr>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= h($r['ROUTE_NAME']) ?></td>
                <td><?= h($r['ORIGIN']) ?> &rarr; <?= h($r['DESTINATION']) ?></td>
                <td><strong><?= (int)$r['TICKETS_SOLD'] ?></strong></td>
                <td><?= (int)$r['TRIPS_RUN'] ?></td>
                <td><?= h($r['AVG_TICKETS_PER_TRIP']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../footer.php'; ?>
