<?php
require_once __DIR__ . '/../../includes/functions.php';
$pageTitle = 'Maintenance Due Report';
require_admin();

$days = (int)($_GET['days'] ?? 30);
$rows = [];
$error = null;
try {
    $rows = call_report('rpt_vehicles_due_maintenance', ['p_days_ahead' => $days]);
} catch (Exception $e) {
    $error = $e->getMessage();
}

require __DIR__ . '/../header.php';
?>
<h1>Report: Vehicles Due for Maintenance</h1>
<p class="muted">Based on <code>rpt_vehicles_due_maintenance</code>.</p>

<div class="card" style="padding:0.5rem 1rem">
    <a href="most_frequent_routes.php">Most frequent routes</a> &middot;
    <a href="revenue.php">Revenue by period</a> &middot;
    <a href="passenger_history.php">Passenger history</a> &middot;
    <a href="maintenance_due.php">Maintenance due</a> &middot;
    <a href="top_rated.php">Top rated</a>
</div>

<div class="card">
    <form method="get">
        <label>Show vehicles due within (days)</label>
        <input type="number" name="days" value="<?= (int)$days ?>" min="0">
        <button type="submit">Run report</button>
    </form>
</div>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php elseif (empty($rows)): ?>
    <p class="muted">No vehicles due for maintenance in this window.</p>
<?php else: ?>
    <table>
        <tr><th>Vehicle</th><th>Type</th><th>Vehicle status</th><th>Next due</th><th>Days until due</th><th>Urgency</th></tr>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= h($r['VEHICLE_NUMBER']) ?></td>
                <td><?= h($r['VEHICLE_TYPE']) ?></td>
                <td><?= h($r['VEHICLE_STATUS']) ?></td>
                <td><?= fmt_date($r['NEXT_DUE_DATE']) ?></td>
                <td><?= (int)$r['DAYS_UNTIL_DUE'] ?></td>
                <td>
                    <span class="badge <?= $r['URGENCY'] === 'OVERDUE' ? 'badge-bad' : 'badge-warn' ?>"><?= h($r['URGENCY']) ?></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../footer.php'; ?>
