<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Book a Ticket';
require_login();

$tripId = isset($_GET['trip_id']) ? (int)$_GET['trip_id'] : (int)($_POST['trip_id'] ?? 0);
$error = null;
$trip = null;

try {
    $rows = oracle_select(
        "SELECT t.trip_id, t.trip_date, t.departure_time, t.fare, t.status,
                r.route_name, r.origin, r.destination,
                v.vehicle_number, v.vehicle_type, v.capacity,
                d.full_name AS driver_name
         FROM trips t
         JOIN routes r ON r.route_id = t.route_id
         JOIN vehicles v ON v.vehicle_id = t.vehicle_id
         JOIN drivers d ON d.driver_id = t.driver_id
         WHERE t.trip_id = :tid",
        [':tid' => $tripId]
    );
    if (empty($rows)) {
        $error = 'Trip not found.';
    } else {
        $trip = $rows[0];
        $trip['available'] = fn_available_seats_call($tripId);
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

$success = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $seat = trim($_POST['seat_number'] ?? '');
    $method = $_POST['payment_method'] ?? 'CARD';
    $passengerId = current_passenger()['passenger_id'];

    if ($seat === '') {
        $error = 'Please enter a seat number.';
    } else {
        try {
            $ticketId = sp_book_ticket_call($tripId, $passengerId, $seat, $method);
            flash('Booked! Your ticket number is #' . $ticketId . '.');
            header('Location: my_tickets.php');
            exit;
        } catch (Exception $e) {
            // sp_book_ticket raises clear ORA-20xxx messages for known cases
            $error = $e->getMessage();
        }
    }
}

require __DIR__ . '/header.php';
?>
<h1>Book a Ticket</h1>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php endif; ?>

<?php if ($trip): ?>
<div class="card" style="max-width:520px">
    <h2 style="margin-top:0"><?= h($trip['ROUTE_NAME']) ?></h2>
    <p class="muted"><?= h($trip['ORIGIN']) ?> &rarr; <?= h($trip['DESTINATION']) ?></p>
    <p><?= fmt_date($trip['TRIP_DATE']) ?> at <?= fmt_datetime($trip['DEPARTURE_TIME']) ?></p>
    <p><?= h($trip['VEHICLE_TYPE']) ?> <?= h($trip['VEHICLE_NUMBER']) ?> &middot; Driver: <?= h($trip['DRIVER_NAME']) ?></p>
    <p><strong>Fare: <?= fmt_money($trip['FARE']) ?></strong> &middot; <?= (int)$trip['available'] ?> seat(s) left of <?= (int)$trip['CAPACITY'] ?></p>

    <?php if ($trip['available'] > 0): ?>
    <form method="post">
        <input type="hidden" name="trip_id" value="<?= (int)$tripId ?>">
        <label>Seat number</label>
        <input type="text" name="seat_number" placeholder="e.g. A5" required>
        <label>Payment method</label>
        <select name="payment_method">
            <option value="CARD">Card</option>
            <option value="ONLINE_WALLET">Online wallet</option>
            <option value="CASH">Cash</option>
            <option value="BANK_TRANSFER">Bank transfer</option>
        </select>
        <button type="submit">Confirm booking</button>
    </form>
    <?php else: ?>
        <p class="badge badge-bad">This trip is fully booked.</p>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
