<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Login';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $which = $_POST['which'] ?? '';

    if ($which === 'passenger') {
        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';
        try {
            $rows = oracle_select(
                "SELECT passenger_id, full_name, email, password_hash, status FROM passengers WHERE email = :email",
                [':email' => $email]
            );
            if (empty($rows)) {
                $errors[] = 'No account found with that email.';
            } else {
                $p = $rows[0];
                if ($p['STATUS'] === 'BANNED') {
                    $errors[] = 'This account has been suspended.';
                } elseif (!password_verify($pass, $p['PASSWORD_HASH'])) {
                    $errors[] = 'Incorrect password.';
                } else {
                    $_SESSION['passenger'] = [
                        'passenger_id' => $p['PASSENGER_ID'],
                        'full_name'    => $p['FULL_NAME'],
                        'email'        => $p['EMAIL'],
                    ];
                    flash('Welcome back, ' . $p['FULL_NAME'] . '!');
                    header('Location: trips.php');
                    exit;
                }
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    } elseif ($which === 'admin') {
        $user = $_POST['admin_user'] ?? '';
        $pass = $_POST['admin_pass'] ?? '';
        if ($user === ADMIN_USERNAME && $pass === ADMIN_PASSWORD) {
            $_SESSION['is_admin'] = true;
            flash('Logged in as admin.');
            header('Location: index.php');
            exit;
        }
        $errors[] = 'Invalid admin credentials.';
    }
}

require __DIR__ . '/header.php';
?>
<h1>Log in</h1>

<?php foreach ($errors as $err): ?>
    <div class="flash flash-error"><?= h($err) ?></div>
<?php endforeach; ?>

<div class="grid-2">
    <div class="card">
        <h2 style="margin-top:0">Passenger</h2>
        <form method="post">
            <input type="hidden" name="which" value="passenger">
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Log in</button>
        </form>
        <p class="muted">No account yet? <a href="register.php">Register here</a>.</p>
        <p class="muted">Note: the sample-data passengers use placeholder password hashes; register a fresh account to test the full booking flow.</p>
    </div>
    <div class="card">
        <h2 style="margin-top:0">Admin / Staff</h2>
        <form method="post">
            <input type="hidden" name="which" value="admin">
            <label>Username</label>
            <input type="text" name="admin_user" required>
            <label>Password</label>
            <input type="password" name="admin_pass" required>
            <button type="submit" class="btn-accent">Log in as admin</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>
