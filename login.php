<?php
require_once __DIR__ . '/includes/auth.php';
$themeClass = getThemeClass();

if (!empty($_SESSION['user'])) {
    header('Location: room.php');
    exit;
}

$error = '';
$success = '';
$usernameVal = '';

if (!empty($_SESSION['flash_success'])) {
    $success = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['toggle_theme'])) {
    $usernameVal = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($usernameVal === '' || $password === '') {
        $error = 'Please enter both your username and password.';
    } else {
        $matchedUser = verifyUserLogin($usernameVal, $password);
        if ($matchedUser !== false) {
            $_SESSION['user'] = $matchedUser;
            startNewVaultRun();
            header('Location: room.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The PHP Vault</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?php echo htmlspecialchars($themeClass, ENT_QUOTES, 'UTF-8'); ?>">
    <header class="site-header">
        <div class="header-inner">
            <a href="index.php" class="brand">The PHP Vault</a>
            <nav class="nav-links">
                <a href="index.php">Home</a>
                <a href="login.php" class="active">Login</a>
                <a href="register.php">Register</a>
                <a href="leaderboard.php">Leaderboard</a>
            </nav>
        </div>
    </header>

    <main class="container narrow">
        <section class="card">
            <h1>Player Login</h1>
            <p>Sign in to start or resume your vault escape run. Demo login: <code>rajan</code> / <code>Vault123</code></p>

            <?php if ($success !== ''): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form method="post" action="login.php" class="vault-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($usernameVal, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn">Enter the Vault</button>
            </form>

            <p class="form-footer">Need an account? <a href="register.php">Register here</a>.</p>
        </section>
    </main>

    <footer class="site-footer">
        <p>Rajan Sharma - CSC 4370 Project 2</p>
    </footer>
</body>
</html>
