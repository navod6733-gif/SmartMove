<?php
require_once __DIR__ . '/../../includes/functions.php';
$pageTitle = 'Passenger Travel History';
require_admin();

$email = trim($_GET['email'] ?? '');
$rows = [];
$passenger = null;
$error = null;

if ($email !== '') {
    try {
        $found = oracle_select("SELECT passenger_id, full_name, email FROM passengers WHERE email = :e", [':e' => $email]);
        if (empty($found)) {
            $error = 'No passenger found with that email.';
        } else {
            $passenger = $found[0];
            $rows = call_report('rpt_passenger_travel_history', ['p_passenger_id' => $passenger['PASSENGER_ID']]);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

require __DIR__ . '/../header.php';
?>
<h1>Report: Passenger Travel History</h1>
<p class="muted">Based on <code>rpt_passenger_travel_history</code>.</p>

<div class="card" style="padding:0.5rem 1rem">
    <a href="most_frequent_routes.php">Most frequent routes</a> &middot;
    <a href="revenue.php">Revenue by period</a> &middot;
    <a href="passenger_history.php">Passenger history</a> &middot;
    <a href="maintenance_due.php">Maintenance due</a> &middot;
    <a href="top_rated.php">Top rated</a>
</div>

<div class="card">
    <form method="get">
        <label>Passenger email</label>
        <input type="email" name="email" value="<?= h($email) ?>" placeholder="dilani.r@example.com" required>
        <button type="submit">Search</button>
    </form>
</div>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php elseif ($passenger): ?>
    <h2><?= h($passenger['FULL_NAME']) ?> (<?= h($passenger['EMAIL']) ?>)</h2>
    <?php if (empty($rows)): ?>
        <p class="muted">No tickets found for this passenger.</p>
    <?php else: ?>
        <table>
            <tr><th>Route</th><th>Date</th><th>Seat</th><th>Price</th><th>Status</th></tr>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= h($r['ROUTE_NAME']) ?></td>
                    <td><?= fmt_date($r['TRIP_DATE']) ?></td>
                    <td><?= h($r['SEAT_NUMBER']) ?></td>
                    <td><?= fmt_money($r['PRICE']) ?></td>
                    <td><?= h($r['TICKET_STATUS']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/../footer.php'; ?>
