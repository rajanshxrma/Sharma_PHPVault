<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/vault_data.php';

requireLogin();
$themeClass = getThemeClass();

if (isset($_POST['play_again'])) {
    startNewVaultRun();
    header('Location: room.php');
    exit;
}

if (empty($_SESSION['vault_run']['completed'])) {
    header('Location: room.php');
    exit;
}

$run = $_SESSION['vault_run'];
$endTime = isset($run['end_time']) ? (int)$run['end_time'] : time();
$elapsedSec = max(1, $endTime - (int)$run['start_time']);
$totalHints = array_sum($run['hints_used']);

if (empty($_SESSION['vault_run']['saved_to_board'])) {
    addLeaderboardEntry(array(
        'username' => $_SESSION['user'],
        'score' => (int)$run['score'],
        'hints' => (int)$totalHints,
        'attempts' => (int)$run['attempts'],
        'time_sec' => $elapsedSec,
        'date' => date('Y-m-d')
    ));
    $_SESSION['vault_run']['saved_to_board'] = true;
}

$minutes = floor($elapsedSec / 60);
$seconds = $elapsedSec % 60;
$timeFormatted = $minutes . 'm ' . $seconds . 's';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vault Escaped - The PHP Vault</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?php echo htmlspecialchars($themeClass, ENT_QUOTES, 'UTF-8'); ?>">
    <header class="site-header">
        <div class="header-inner">
            <a href="index.php" class="brand">The PHP Vault</a>
            <nav class="nav-links">
                <a href="index.php">Home</a>
                <a href="results.php" class="active">Results</a>
                <a href="leaderboard.php">Leaderboard</a>
                <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8'); ?>)</a>
            </nav>
        </div>
    </header>

    <main class="container narrow">
        <section class="card">
            <h1>You Escaped the Vault!</h1>
            <p>Nice job, <?php echo htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8'); ?>. You unlocked all 3 rooms and your score was added to the leaderboard.</p>

            <table class="board-table">
                <tbody>
                    <tr>
                        <th>Final Score</th>
                        <td><strong><?php echo (int)$run['score']; ?> pts</strong></td>
                    </tr>
                    <tr>
                        <th>Total Time</th>
                        <td><?php echo htmlspecialchars($timeFormatted, ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <tr>
                        <th>Total Attempts</th>
                        <td><?php echo (int)$run['attempts']; ?></td>
                    </tr>
                    <tr>
                        <th>Hints Used</th>
                        <td><?php echo (int)$totalHints; ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="action-row">
                <a href="leaderboard.php" class="btn">View Leaderboard</a>
                <form method="post" action="results.php" class="inline-form">
                    <button type="submit" name="play_again" value="1" class="btn btn-secondary">Play Again</button>
                </form>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <p>Rajan Sharma - CSC 4370 Project 2</p>
    </footer>
</body>
</html>
