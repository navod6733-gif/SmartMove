<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Leave Feedback';
require_login();

$tripId = (int)($_GET['trip_id'] ?? $_POST['trip_id'] ?? 0);
$passengerId = current_passenger()['passenger_id'];
$error = null;

$trip = null;
try {
    $rows = oracle_select(
        "SELECT t.trip_id, t.route_id, t.vehicle_id, t.driver_id, t.trip_date,
                r.route_name, r.origin, r.destination
         FROM trips t JOIN routes r ON r.route_id = t.route_id
         WHERE t.trip_id = :tid",
        [':tid' => $tripId]
    );
    if (empty($rows)) {
        $error = 'Trip not found.';
    } else {
        $trip = $rows[0];
    }

    // Make sure this passenger actually completed a ticket on this trip
    if ($trip) {
        $owns = oracle_select(
            "SELECT COUNT(*) AS c FROM tickets WHERE trip_id = :tid AND passenger_id = :pid AND status = 'COMPLETED'",
            [':tid' => $tripId, ':pid' => $passengerId]
        );
        if ((int)($owns[0]['C'] ?? 0) === 0) {
            $error = 'You can only leave feedback for trips you have completed.';
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($rating < 1 || $rating > 5) {
        $error = 'Please choose a rating between 1 and 5.';
    } else {
        try {
            // 1) Structured rating -> Oracle
            $feedbackId = oracle_insert_returning_id(
                "INSERT INTO feedback (passenger_id, trip_id, rating) VALUES (:pid, :tid, :rating) RETURNING feedback_id INTO :new_id",
                [':pid' => $passengerId, ':tid' => $tripId, ':rating' => $rating]
            );

            // 2) Free-text comment -> MongoDB, linked back via oracle_feedback_id
            if ($comment !== '') {
                mongo_insert('reviews', [
                    'oracle_feedback_id' => (int)$feedbackId,
                    'passenger_id' => (int)$passengerId,
                    'trip_id'      => (int)$tripId,
                    'route_id'     => (int)$trip['ROUTE_ID'],
                    'route_name'   => $trip['ROUTE_NAME'],
                    'driver_id'    => (int)$trip['DRIVER_ID'],
                    'vehicle_id'   => (int)$trip['VEHICLE_ID'],
                    'rating'       => $rating,
                    'comment'      => $comment,
                    'tags'         => [],
                    'reported'     => $rating <= 2,
                ]);
            }

            flash('Thanks for your feedback!');
            header('Location: my_tickets.php');
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

require __DIR__ . '/header.php';
?>
<h1>Leave Feedback</h1>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php else: ?>
    <div class="card" style="max-width:520px">
        <h2 style="margin-top:0"><?= h($trip['ROUTE_NAME']) ?></h2>
        <p class="muted"><?= h($trip['ORIGIN']) ?> &rarr; <?= h($trip['DESTINATION']) ?> &middot; <?= fmt_date($trip['TRIP_DATE']) ?></p>
        <form method="post">
            <input type="hidden" name="trip_id" value="<?= (int)$tripId ?>">
            <label>Rating</label>
            <select name="rating" required>
                <option value="">Select a rating</option>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?= $i ?>"><?= str_repeat('★', $i) . str_repeat('☆', 5 - $i) ?></option>
                <?php endfor; ?>
            </select>
            <label>Comment (optional)</label>
            <textarea name="comment" rows="4" placeholder="Tell us about your trip..."></textarea>
            <button type="submit">Submit feedback</button>
        </form>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
