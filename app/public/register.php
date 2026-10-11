<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Register';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $pass2 = $_POST['password_confirm'] ?? '';

    if ($name === '' || $email === '' || $phone === '' || $pass === '') {
        $errors[] = 'All fields are required.';
    }
    if ($pass !== $pass2) {
        $errors[] = 'Passwords do not match.';
    }
    if (strlen($pass) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (empty($errors)) {
        try {
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $passengerId = sp_register_passenger_call($name, $email, $phone, $hash);
            $_SESSION['passenger'] = [
                'passenger_id' => $passengerId,
                'full_name'    => $name,
                'email'        => $email,
            ];
            flash('Welcome to SmartMove, ' . $name . '!');
            header('Location: trips.php');
            exit;
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

require __DIR__ . '/header.php';
?>
<h1>Create your passenger account</h1>

<?php foreach ($errors as $err): ?>
    <div class="flash flash-error"><?= h($err) ?></div>
<?php endforeach; ?>

<div class="card" style="max-width:480px">
    <form method="post">
        <label>Full name</label>
        <input type="text" name="full_name" required value="<?= h($_POST['full_name'] ?? '') ?>">

        <label>Email</label>
        <input type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>">

        <label>Phone</label>
        <input type="text" name="phone" required value="<?= h($_POST['phone'] ?? '') ?>">

        <label>Password</label>
        <input type="password" name="password" required minlength="6">

        <label>Confirm password</label>
        <input type="password" name="password_confirm" required minlength="6">

        <button type="submit">Register</button>
    </form>
</div>
<p class="muted">Already have an account? <a href="login.php">Log in</a></p>

<?php require __DIR__ . '/footer.php'; ?>
