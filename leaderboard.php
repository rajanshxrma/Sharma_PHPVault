<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/vault_data.php';

$themeClass = getThemeClass();
$board = loadLeaderboard();
$loggedInUser = isset($_SESSION['user']) ? $_SESSION['user'] : null;

if (isset($_POST['play_again']) && $loggedInUser) {
    startNewVaultRun();
    header('Location: room.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - The PHP Vault</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?php echo htmlspecialchars($themeClass, ENT_QUOTES, 'UTF-8'); ?>">
    <header class="site-header">
        <div class="header-inner">
            <a href="index.php" class="brand">The PHP Vault</a>
            <nav class="nav-links">
                <a href="index.php">Home</a>
                <?php if ($loggedInUser): ?>
                    <a href="room.php">Current Room</a>
                    <a href="leaderboard.php" class="active">Leaderboard</a>
                    <a href="logout.php">Logout (<?php echo htmlspecialchars($loggedInUser, ENT_QUOTES, 'UTF-8'); ?>)</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                    <a href="leaderboard.php" class="active">Leaderboard</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="card">
            <h1>Vault Escape Leaderboard</h1>
            <p>Top completed runs ranked by highest score first and fastest time second.</p>

            <?php if (empty($board)): ?>
                <p class="muted-note">No escape runs recorded yet. Log in and be the first to crack the vault!</p>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="board-table">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Player</th>
                                <th>Score</th>
                                <th>Hints Used</th>
                                <th>Attempts</th>
                                <th>Time</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($board as $idx => $row): ?>
                                <?php
                                $mins = floor(((int)$row['time_sec']) / 60);
                                $secs = ((int)$row['time_sec']) % 60;
                                ?>
                                <tr>
                                    <td>#<?php echo ($idx + 1); ?></td>
                                    <td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><strong><?php echo (int)$row['score']; ?> pts</strong></td>
                                    <td><?php echo (int)$row['hints']; ?></td>
                                    <td><?php echo (int)$row['attempts']; ?></td>
                                    <td><?php echo $mins . 'm ' . $secs . 's'; ?></td>
                                    <td><?php echo htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="action-row">
                <?php if ($loggedInUser): ?>
                    <form method="post" action="leaderboard.php" class="inline-form">
                        <button type="submit" name="play_again" value="1" class="btn">Start New Run</button>
                    </form>
                <?php else: ?>
                    <a href="login.php" class="btn">Login to Play</a>
                <?php endif; ?>
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <p>Rajan Sharma - CSC 4370 Project 2</p>
    </footer>
</body>
</html>
