<?php
require_once __DIR__ . '/../../includes/functions.php';
$pageTitle = 'Revenue Report';
require_admin();

$start = $_GET['start'] ?? date('Y-m-01');
$end   = $_GET['end'] ?? date('Y-m-d');

$rows = [];
$total = 0;
$error = null;
try {
    $rows = call_report('rpt_revenue_by_period', ['p_start_date' => $start, 'p_end_date' => $end]);
    $conn = db_oracle();
    $sql = "BEGIN :r := fn_total_revenue_in_period(TO_DATE(:s,'YYYY-MM-DD'), TO_DATE(:e,'YYYY-MM-DD')); END;";
    $stmt = oci_parse($conn, $sql);
    $r = null;
    oci_bind_by_name($stmt, ':r', $r, 20);
    oci_bind_by_name($stmt, ':s', $start);
    oci_bind_by_name($stmt, ':e', $end);
    oci_execute($stmt);
    $total = (float)$r;
    oci_free_statement($stmt);
} catch (Exception $e) {
    $error = $e->getMessage();
}

require __DIR__ . '/../header.php';
?>
<h1>Report: Revenue by Period</h1>
<p class="muted">Based on <code>rpt_revenue_by_period</code> and <code>fn_total_revenue_in_period</code>.</p>

<div class="card" style="padding:0.5rem 1rem">
    <a href="most_frequent_routes.php">Most frequent routes</a> &middot;
    <a href="revenue.php">Revenue by period</a> &middot;
    <a href="passenger_history.php">Passenger history</a> &middot;
    <a href="maintenance_due.php">Maintenance due</a> &middot;
    <a href="top_rated.php">Top rated</a>
</div>

<div class="card">
    <form method="get" class="grid-2">
        <div>
            <label>From</label>
            <input type="date" name="start" value="<?= h($start) ?>">
        </div>
        <div>
            <label>To</label>
            <input type="date" name="end" value="<?= h($end) ?>">
        </div>
        <div style="grid-column:1/-1"><button type="submit">Run report</button></div>
    </form>
</div>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php else: ?>
    <div class="card">
        <h2 style="margin-top:0">Total revenue: <?= fmt_money($total) ?></h2>
    </div>
    <?php if (empty($rows)): ?>
        <p class="muted">No successful payments in this period.</p>
    <?php else: ?>
        <table>
            <tr><th>Day</th><th>Route</th><th>Payments</th><th>Revenue</th></tr>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= fmt_date($r['PAYMENT_DAY']) ?></td>
                    <td><?= h($r['ROUTE_NAME']) ?></td>
                    <td><?= (int)$r['PAYMENTS_COUNT'] ?></td>
                    <td><?= fmt_money($r['TOTAL_REVENUE']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/../footer.php'; ?>
