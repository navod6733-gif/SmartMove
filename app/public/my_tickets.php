<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'My Tickets';
require_login();

$passengerId = current_passenger()['passenger_id'];
$tickets = [];
$error = null;
try {
    $tickets = call_report('rpt_passenger_travel_history', ['p_passenger_id' => $passengerId]);
} catch (Exception $e) {
    $error = $e->getMessage();
}

require __DIR__ . '/header.php';
?>
<h1>My Tickets</h1>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php elseif (empty($tickets)): ?>
    <p class="muted">You haven't booked any trips yet. <a href="trips.php">Browse trips</a>.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Route</th><th>Date</th><th>Seat</th><th>Price</th><th>Status</th><th>Payment</th><th></th>
        </tr>
        <?php foreach ($tickets as $t): ?>
            <tr>
                <td><?= h($t['ROUTE_NAME']) ?><br><span class="muted"><?= h($t['ORIGIN']) ?> &rarr; <?= h($t['DESTINATION']) ?></span></td>
                <td><?= fmt_date($t['TRIP_DATE']) ?></td>
                <td><?= h($t['SEAT_NUMBER']) ?></td>
                <td><?= fmt_money($t['PRICE']) ?></td>
                <td>
                    <?php
                        $badge = 'badge-warn';
                        if ($t['TICKET_STATUS'] === 'COMPLETED') $badge = 'badge-ok';
                        if ($t['TICKET_STATUS'] === 'CANCELLED') $badge = 'badge-bad';
                    ?>
                    <span class="badge <?= $badge ?>"><?= h($t['TICKET_STATUS']) ?></span>
                </td>
                <td><?= h($t['PAYMENT_STATUS'] ?? '-') ?> (<?= h($t['PAYMENT_METHOD'] ?? '-') ?>)</td>
                <td>
                    <?php if ($t['TICKET_STATUS'] === 'BOOKED'): ?>
                        <form method="post" action="cancel_ticket.php" onsubmit="return confirm('Cancel this ticket?');" style="display:inline">
                            <input type="hidden" name="ticket_id" value="<?= (int)$t['TICKET_ID'] ?>">
                            <button type="submit" class="btn-danger" style="margin:0;padding:0.35rem 0.7rem;font-size:0.8rem">Cancel</button>
                        </form>
                    <?php elseif ($t['TICKET_STATUS'] === 'COMPLETED'): ?>
                        <a class="btn" style="margin:0;padding:0.35rem 0.7rem;font-size:0.8rem" href="feedback.php?trip_id=<?= (int)$t['TRIP_ID'] ?>">Leave feedback</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
