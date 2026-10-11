<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Vehicles';
require_admin();

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        oracle_execute(
            "INSERT INTO vehicles (vehicle_number, vehicle_type, capacity, model, manufacture_year, status)
             VALUES (:num, :type, :cap, :model, :year, 'ACTIVE')",
            [
                ':num' => trim($_POST['vehicle_number']),
                ':type' => $_POST['vehicle_type'],
                ':cap' => (int)$_POST['capacity'],
                ':model' => trim($_POST['model']),
                ':year' => (int)$_POST['manufacture_year'],
            ]
        );
        flash('Vehicle added.');
        header('Location: vehicles.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$vehicles = [];
try {
    $vehicles = oracle_select("SELECT vehicle_id, vehicle_number, vehicle_type, capacity, model, manufacture_year, status FROM vehicles ORDER BY vehicle_number");
} catch (Exception $e) {
    $error = $error ?: $e->getMessage();
}

require __DIR__ . '/header.php';
?>
<h1>Vehicles</h1>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php endif; ?>

<div class="card">
    <h2 style="margin-top:0">Add Vehicle</h2>
    <form method="post" class="grid-2">
        <div>
            <label>Vehicle number</label>
            <input type="text" name="vehicle_number" required placeholder="SM-B-005">
            <label>Type</label>
            <select name="vehicle_type" required>
                <option value="BUS">Bus</option>
                <option value="VAN">Van</option>
                <option value="MINIBUS">Minibus</option>
                <option value="CAR">Car</option>
            </select>
        </div>
        <div>
            <label>Capacity</label>
            <input type="number" name="capacity" min="1" required>
            <label>Model</label>
            <input type="text" name="model">
            <label>Manufacture year</label>
            <input type="number" name="manufacture_year" min="1990" max="2100">
        </div>
        <div style="grid-column:1/-1">
            <button type="submit">Add vehicle</button>
        </div>
    </form>
</div>

<table>
    <tr><th>Number</th><th>Type</th><th>Capacity</th><th>Model</th><th>Year</th><th>Status</th><th></th></tr>
    <?php foreach ($vehicles as $v): ?>
        <tr>
            <td><?= h($v['VEHICLE_NUMBER']) ?></td>
            <td><?= h($v['VEHICLE_TYPE']) ?></td>
            <td><?= (int)$v['CAPACITY'] ?></td>
            <td><?= h($v['MODEL']) ?></td>
            <td><?= h($v['MANUFACTURE_YEAR']) ?></td>
            <td>
                <?php $b = $v['STATUS'] === 'ACTIVE' ? 'badge-ok' : ($v['STATUS'] === 'MAINTENANCE' ? 'badge-warn' : 'badge-bad'); ?>
                <span class="badge <?= $b ?>"><?= h($v['STATUS']) ?></span>
            </td>
            <td><a href="vehicle_docs.php?vehicle_id=<?= (int)$v['VEHICLE_ID'] ?>">Documents</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require __DIR__ . '/footer.php'; ?>
