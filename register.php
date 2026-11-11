<?php
require_once __DIR__ . '/includes/auth.php';
$themeClass = getThemeClass();

if (!empty($_SESSION['user'])) {
    header('Location: room.php');
    exit;
}

$error = '';
$usernameVal = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['toggle_theme'])) {
    $usernameVal = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirm = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    if ($usernameVal === '' || $password === '') {
        $error = 'Please fill in both username and password.';
    } elseif (strlen($usernameVal) < 3 || strlen($usernameVal) > 20) {
        $error = 'Username must be between 3 and 20 characters.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $usernameVal)) {
        $error = 'Username can only use letters, numbers, and underscores.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $res = registerUser($usernameVal, $password);
        if ($res['ok']) {
            $_SESSION['flash_success'] = $res['msg'];
            header('Location: login.php');
            exit;
        } else {
            $error = $res['msg'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - The PHP Vault</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?php echo htmlspecialchars($themeClass, ENT_QUOTES, 'UTF-8'); ?>">
    <header class="site-header">
        <div class="header-inner">
            <a href="index.php" class="brand">The PHP Vault</a>
            <nav class="nav-links">
                <a href="index.php">Home</a>
                <a href="login.php">Login</a>
                <a href="register.php" class="active">Register</a>
                <a href="leaderboard.php">Leaderboard</a>
            </nav>
        </div>
    </header>

    <main class="container narrow">
        <section class="card">
            <h1>Create an Account</h1>
            <p>Register a player profile to track your puzzle progress and post scores to the leaderboard.</p>

            <?php if ($error !== ''): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form method="post" action="register.php" class="vault-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($usernameVal, ENT_QUOTES, 'UTF-8'); ?>" placeholder="3 to 20 letters or numbers" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="At least 6 characters" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter password" required>
                </div>

                <button type="submit" class="btn">Register Account</button>
            </form>

            <p class="form-footer">Already have an account? <a href="login.php">Log in here</a>.</p>
        </section>
    </main>

    <footer class="site-footer">
        <p>Rajan Sharma - CSC 4370 Project 2</p>
    </footer>
</body>
</html>
