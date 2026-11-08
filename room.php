<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/vault_data.php';

requireLogin();
$themeClass = getThemeClass();
$rooms = getVaultRooms();

if (isset($_POST['reset_run'])) {
    startNewVaultRun();
    $_SESSION['room_feedback'] = array('type' => 'info', 'text' => 'Started a fresh escape run at Room 1 with 300 points.');
    header('Location: room.php');
    exit;
}

if (!empty($_SESSION['vault_run']['completed'])) {
    header('Location: results.php');
    exit;
}

$currentRoomId = (int)$_SESSION['vault_run']['current_room'];
if (!isset($rooms[$currentRoomId])) {
    $currentRoomId = 1;
    $_SESSION['vault_run']['current_room'] = 1;
}
$room = $rooms[$currentRoomId];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['toggle_theme'])) {
    if (isset($_POST['request_hint'])) {
        $used = isset($_SESSION['vault_run']['hints_used'][$currentRoomId])
            ? (int)$_SESSION['vault_run']['hints_used'][$currentRoomId]
            : 0;
        $nextTier = $used + 1;
        $cost = getHintCost($nextTier);
        $_SESSION['vault_run']['hints_used'][$currentRoomId] = $nextTier;
        $_SESSION['vault_run']['score'] = max(0, (int)$_SESSION['vault_run']['score'] - $cost);
        $_SESSION['room_feedback'] = array(
            'type' => 'info',
            'text' => 'Unlocked Hint ' . $nextTier . ' (-' . $cost . ' points).'
        );
    }

    if (isset($_POST['submit_answer'])) {
        $rawGuess = isset($_POST['answer']) ? trim($_POST['answer']) : '';
        if ($rawGuess === '') {
            $_SESSION['room_feedback'] = array(
                'type' => 'error',
                'text' => 'Please enter an answer before submitting (no points deducted).'
            );
        } else {
            $_SESSION['vault_run']['attempts'] = (int)$_SESSION['vault_run']['attempts'] + 1;
            $cleanGuess = strtoupper($rawGuess);
            $expected = strtoupper($room['answer']);

            if ($cleanGuess === $expected) {
                if ($currentRoomId < 3) {
                    $_SESSION['vault_run']['current_room'] = $currentRoomId + 1;
                    $_SESSION['room_feedback'] = array(
                        'type' => 'success',
                        'text' => 'Correct code! Room ' . $currentRoomId . ' unlocked. Welcome to Room ' . ($currentRoomId + 1) . '.'
                    );
                    header('Location: room.php');
                    exit;
                } else {
                    $_SESSION['vault_run']['completed'] = true;
                    $_SESSION['vault_run']['end_time'] = time();
                    header('Location: results.php');
                    exit;
                }
            } else {
                $_SESSION['vault_run']['score'] = max(0, (int)$_SESSION['vault_run']['score'] - 10);
                $_SESSION['room_feedback'] = array(
                    'type' => 'error',
                    'text' => 'Incorrect code "' . $rawGuess . '". Subtracted 10 points. Try again or unlock a hint.'
                );
            }
        }
    }
}

$feedback = null;
if (!empty($_SESSION['room_feedback'])) {
    $feedback = $_SESSION['room_feedback'];
    unset($_SESSION['room_feedback']);
}

$usedHints = isset($_SESSION['vault_run']['hints_used'][$currentRoomId])
    ? (int)$_SESSION['vault_run']['hints_used'][$currentRoomId]
    : 0;
$nextHintCost = ($usedHints < 3) ? getHintCost($usedHints + 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($room['title'], ENT_QUOTES, 'UTF-8'); ?> - The PHP Vault</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="<?php echo htmlspecialchars($themeClass, ENT_QUOTES, 'UTF-8'); ?>">
    <header class="site-header">
        <div class="header-inner">
            <a href="index.php" class="brand">The PHP Vault</a>
            <nav class="nav-links">
                <a href="index.php">Home</a>
                <a href="room.php" class="active">Current Room</a>
                <a href="leaderboard.php">Leaderboard</a>
                <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8'); ?>)</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="status-bar card">
            <div><strong>Player:</strong> <?php echo htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8'); ?></div>
            <div><strong>Progress:</strong> Room <?php echo $currentRoomId; ?> of 3</div>
            <div><strong>Score:</strong> <?php echo (int)$_SESSION['vault_run']['score']; ?> pts</div>
            <div><strong>Attempts:</strong> <?php echo (int)$_SESSION['vault_run']['attempts']; ?></div>
        </section>

        <?php if ($feedback): ?>
            <div class="alert alert-<?php echo htmlspecialchars($feedback['type'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($feedback['text'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <div class="room-layout">
            <section class="card room-main">
                <div class="room-steps">
                    <span class="step-pill <?php echo ($currentRoomId >= 1) ? 'done' : ''; ?>">Room 1</span>
                    <span class="step-pill <?php echo ($currentRoomId >= 2) ? 'done' : ''; ?>">Room 2</span>
                    <span class="step-pill <?php echo ($currentRoomId >= 3) ? 'done' : ''; ?>">Room 3</span>
                </div>

                <h1><?php echo htmlspecialchars($room['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <p><?php echo htmlspecialchars($room['story'], ENT_QUOTES, 'UTF-8'); ?></p>

                <p class="prompt-label"><strong><?php echo htmlspecialchars($room['prompt'], ENT_QUOTES, 'UTF-8'); ?></strong></p>
                <pre class="clue-box"><?php echo htmlspecialchars($room['clue_box'], ENT_QUOTES, 'UTF-8'); ?></pre>

                <form method="post" action="room.php" class="vault-form">
                    <div class="form-group">
                        <label for="answer">Your Answer</label>
                        <input type="text" id="answer" name="answer" placeholder="<?php echo htmlspecialchars($room['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
                    </div>
                    <button type="submit" name="submit_answer" value="1" class="btn">Submit Code</button>
                </form>
            </section>

            <aside class="card room-sidebar">
                <h2>Tiered Hint System</h2>
                <p>Stuck on this lock? Unlock up to 3 progressive hints for this room.</p>

                <?php if ($usedHints === 0): ?>
                    <p class="muted-note">No hints unlocked for Room <?php echo $currentRoomId; ?> yet.</p>
                <?php else: ?>
                    <ul class="hint-list">
                        <?php for ($t = 1; $t <= $usedHints; $t++): ?>
                            <li><?php echo htmlspecialchars(getVaultHint($currentRoomId, $t), ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endfor; ?>
                    </ul>
                <?php endif; ?>

                <form method="post" action="room.php" class="hint-form">
                    <?php if ($usedHints < 3): ?>
                        <button type="submit" name="request_hint" value="1" class="btn btn-secondary">
                            Get Hint <?php echo ($usedHints + 1); ?> (-<?php echo $nextHintCost; ?> pts)
                        </button>
                    <?php else: ?>
                        <button type="submit" name="request_hint" value="1" class="btn btn-secondary">
                            All 3 Hints Unlocked (0 pts)
                        </button>
                    <?php endif; ?>
                </form>

                <hr class="divider">

                <form method="post" action="room.php">
                    <button type="submit" name="reset_run" value="1" class="btn-small">Restart Run (Room 1)</button>
                </form>
            </aside>
        </div>
    </main>

    <footer class="site-footer">
        <p>Rajan Sharma - CSC 4370 Project 2</p>
    </footer>
</body>
</html>
