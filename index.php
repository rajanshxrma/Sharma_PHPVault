<?php
require_once __DIR__ . '/includes/auth.php';
$themeClass = getThemeClass();
$loggedInUser = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The PHP Vault</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?php echo htmlspecialchars($themeClass, ENT_QUOTES, 'UTF-8'); ?>">
    <header class="site-header">
        <div class="header-inner">
            <a href="index.php" class="brand">The PHP Vault</a>
            <nav class="nav-links">
                <a href="index.php" class="active">Home</a>
                <?php if ($loggedInUser): ?>
                    <a href="room.php">Current Room</a>
                    <a href="leaderboard.php">Leaderboard</a>
                    <a href="logout.php">Logout (<?php echo htmlspecialchars($loggedInUser, ENT_QUOTES, 'UTF-8'); ?>)</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                    <a href="leaderboard.php">Leaderboard</a>
                <?php endif; ?>
                <form method="post" action="index.php" class="inline-form">
                    <button type="submit" name="toggle_theme" value="1" class="btn-small">Dark / Light</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="card">
            <h1>Welcome to the Vault</h1>
            <p>Log in or create an account to try escaping all 3 locked rooms in order. Every new run starts at 300 points.</p>

            <h2>Rooms</h2>
            <ul class="simple-list">
                <li>Room 1: Shift cipher terminal on the outer door</li>
                <li>Room 2: Number pattern on the elevator fuse box</li>
                <li>Room 3: 4-digit keypad on the main vault door</li>
            </ul>

            <h2>Scoring and Hints</h2>
            <p>Each wrong guess takes off 10 points. If you get stuck in a room, you can unlock up to 3 hints (10 points for Hint 1, 20 points for Hint 2, and 30 points for Hint 3). Finished runs are saved to the leaderboard.</p>

            <?php if ($loggedInUser): ?>
                <div class="action-row">
                    <a href="room.php" class="btn">Continue Run</a>
                    <a href="leaderboard.php" class="btn btn-secondary">Leaderboard</a>
                </div>
            <?php else: ?>
                <div class="action-row">
                    <a href="login.php" class="btn">Login</a>
                    <a href="register.php" class="btn btn-secondary">Register</a>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        <p>Rajan Sharma - CSC 4370 Project 2</p>
    </footer>
</body>
</html>
