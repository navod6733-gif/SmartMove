<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Maintenance';
require_admin();

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $binds = [
            ':vid' => (int)$_POST['vehicle_id'],
            ':mdate' => $_POST['maintenance_date'],
            ':desc' => trim($_POST['description']),
            ':cost' => (float)$_POST['cost'],
            ':status' => $_POST['status'],
        ];
        $sql = "INSERT INTO maintenance (vehicle_id, maintenance_date, description, cost, status)
                VALUES (:vid, TO_DATE(:mdate,'YYYY-MM-DD'), :desc, :cost, :status)";
        // trg_maintenance_default_due_date fills next_due_date automatically;
        // trg_maintenance_vehicle_status flips the vehicle's status automatically.
        oracle_execute($sql, $binds);
        flash('Maintenance record added. Vehicle status updated automatically by trigger.');
        header('Location: maintenance.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$vehicles = $records = [];
try {
    $vehicles = oracle_select("SELECT vehicle_id, vehicle_number FROM vehicles ORDER BY vehicle_number");
    $records = oracle_select(
        "SELECT m.maintenance_id, v.vehicle_number, m.maintenance_date, m.description, m.cost, m.next_due_date, m.status
         FROM maintenance m JOIN vehicles v ON v.vehicle_id = m.vehicle_id
         ORDER BY m.maintenance_date DESC"
    );
} catch (Exception $e) {
    $error = $error ?: $e->getMessage();
}

require __DIR__ . '/header.php';
?>
<h1>Maintenance</h1>

<?php if ($error): ?>
    <div class="flash flash-error"><?= h($error) ?></div>
<?php endif; ?>

<div class="card">
    <h2 style="margin-top:0">Log Maintenance</h2>
    <form method="post" class="grid-2">
        <div>
            <label>Vehicle</label>
            <select name="vehicle_id" required>
                <?php foreach ($vehicles as $v): ?>
                    <option value="<?= (int)$v['VEHICLE_ID'] ?>"><?= h($v['VEHICLE_NUMBER']) ?></option>
                <?php endforeach; ?>
            </select>
            <label>Date</label>
            <input type="date" name="maintenance_date" required>
            <label>Status</label>
            <select name="status">
                <option value="COMPLETED">Completed</option>
                <option value="IN_PROGRESS">In progress</option>
                <option value="SCHEDULED">Scheduled</option>
            </select>
        </div>
        <div>
            <label>Description</label>
            <textarea name="description" rows="3" required></textarea>
            <label>Cost (LKR)</label>
            <input type="number" step="0.01" name="cost" required>
        </div>
        <div style="grid-column:1/-1"><button type="submit">Save record</button></div>
    </form>
</div>

<h2>Maintenance History</h2>
<table>
    <tr><th>Vehicle</th><th>Date</th><th>Description</th><th>Cost</th><th>Next due</th><th>Status</th></tr>
    <?php foreach ($records as $r): ?>
        <tr>
            <td><?= h($r['VEHICLE_NUMBER']) ?></td>
            <td><?= fmt_date($r['MAINTENANCE_DATE']) ?></td>
            <td><?= h($r['DESCRIPTION']) ?></td>
            <td><?= fmt_money($r['COST']) ?></td>
            <td><?= fmt_date($r['NEXT_DUE_DATE']) ?></td>
            <td>
                <?php $b = $r['STATUS'] === 'COMPLETED' ? 'badge-ok' : 'badge-warn'; ?>
                <span class="badge <?= $b ?>"><?= h($r['STATUS']) ?></span>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<p><a class="btn" href="reports/maintenance_due.php">View "Vehicles due for maintenance" report</a></p>

<?php require __DIR__ . '/footer.php'; ?>
