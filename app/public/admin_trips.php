<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Trip Scheduling';
require_admin();

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_trip') {
    try {
        oracle_execute(
            "INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, fare, status)
             VALUES (:rid, :vid, :did, TO_DATE(:tdate,'YYYY-MM-DD'),
                     TO_DATE(:tdate || ' ' || :ttime, 'YYYY-MM-DD HH24:MI'), :fare, 'SCHEDULED')",
            [
                ':rid' => (int)$_POST['route_id'],
                ':vid' => (int)$_POST['vehicle_id'],
                ':did' => (int)$_POST['driver_id'],
                ':tdate' => $_POST['trip_date'],
                ':ttime' => $_POST['departure_time'],
                ':fare' => (float)$_POST['fare'],
            ]
        );
        flash('Trip scheduled.');
        header('Location: admin_trips.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'assign') {
    try {
        sp_assign_driver_vehicle_call((int)$_POST['driver_id'], (int)$_POST['vehicle_id']);
        flash('Driver assigned to vehicle.');
        header('Location: admin_trips.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$routes = $vehicles = $drivers = $trips = [];
try {
    $routes = oracle_select("SELECT route_id, route_name FROM routes WHERE status='ACTIVE' ORDER BY route_name");
    $vehicles = oracle_select("SELECT vehicle_id, vehicle_number FROM vehicles WHERE status='ACTIVE' ORDER BY vehicle_number");
    $drivers = oracle_select("SELECT driver_id, full_name FROM drivers WHERE status='ACTIVE' ORDER BY full_name");
    $trips = oracle_select(
        "SELECT t.trip_id, r.route_name, v.vehicle_number, d.full_name AS driver_name,
                t.trip_date, t.departure_time, t.fare, t.status
         FROM trips t
         JOIN routes r ON r.route_id = t.route_id
         JOIN vehicles v ON v.vehicle_id = t.vehicle_id
         JOIN drivers d ON d.driver_id = t.driver_id
         ORDER BY t.trip_date DESC, t.departure_time DESC"
    );
} catch (Exception $e) {
    $error = $error ?: $e->getMessage();
}

require __DIR__ . '/header.php';
?>
<h1>Trip Scheduling</h1>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php endif; ?>

<div class="grid-2">
    <div class="card">
        <h2 style="margin-top:0">Schedule a Trip</h2>
        <form method="post">
            <input type="hidden" name="action" value="add_trip">
            <label>Route</label>
            <select name="route_id" required>
                <?php foreach ($routes as $r): ?>
                    <option value="<?= (int)$r['ROUTE_ID'] ?>"><?= h($r['ROUTE_NAME']) ?></option>
                <?php endforeach; ?>
            </select>
            <label>Vehicle</label>
            <select name="vehicle_id" required>
                <?php foreach ($vehicles as $v): ?>
                    <option value="<?= (int)$v['VEHICLE_ID'] ?>"><?= h($v['VEHICLE_NUMBER']) ?></option>
                <?php endforeach; ?>
            </select>
            <label>Driver</label>
            <select name="driver_id" required>
                <?php foreach ($drivers as $d): ?>
                    <option value="<?= (int)$d['DRIVER_ID'] ?>"><?= h($d['FULL_NAME']) ?></option>
                <?php endforeach; ?>
            </select>
            <label>Trip date</label>
            <input type="date" name="trip_date" required>
            <label>Departure time</label>
            <input type="time" name="departure_time" required>
            <label>Fare (LKR)</label>
            <input type="number" step="0.01" name="fare" required>
            <button type="submit">Schedule trip</button>
        </form>
    </div>

    <div class="card">
        <h2 style="margin-top:0">Assign Driver to Vehicle</h2>
        <p class="muted">Ends the vehicle's current assignment and starts a new one.</p>
        <form method="post">
            <input type="hidden" name="action" value="assign">
            <label>Driver</label>
            <select name="driver_id" required>
                <?php foreach ($drivers as $d): ?>
                    <option value="<?= (int)$d['DRIVER_ID'] ?>"><?= h($d['FULL_NAME']) ?></option>
                <?php endforeach; ?>
            </select>
            <label>Vehicle</label>
            <select name="vehicle_id" required>
                <?php foreach ($vehicles as $v): ?>
                    <option value="<?= (int)$v['VEHICLE_ID'] ?>"><?= h($v['VEHICLE_NUMBER']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn-accent">Assign</button>
        </form>
    </div>
</div>

<h2>All Trips</h2>
<table>
    <tr><th>Route</th><th>Vehicle</th><th>Driver</th><th>Date</th><th>Departs</th><th>Fare</th><th>Status</th></tr>
    <?php foreach ($trips as $t): ?>
        <tr>
            <td><?= h($t['ROUTE_NAME']) ?></td>
            <td><?= h($t['VEHICLE_NUMBER']) ?></td>
            <td><?= h($t['DRIVER_NAME']) ?></td>
            <td><?= fmt_date($t['TRIP_DATE']) ?></td>
            <td><?= fmt_datetime($t['DEPARTURE_TIME']) ?></td>
            <td><?= fmt_money($t['FARE']) ?></td>
            <td><?= h($t['STATUS']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require __DIR__ . '/footer.php'; ?>
