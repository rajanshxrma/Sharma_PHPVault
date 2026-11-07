<?php
function getVaultRooms() {
    return array(
        1 => array(
            'id' => 1,
            'title' => 'Room 1: Cipher Terminal',
            'story' => 'The outer steel door is locked by a shift cipher terminal. A sticky note on the monitor says every letter was shifted forward by 3 positions in the alphabet (for example, KHOOR decodes to HELLO).',
            'prompt' => 'Decode the 5-letter passkey shown on the terminal screen:',
            'clue_box' => 'ENCRYPTED PASSKEY: Y D X O W   (Shift: +3)',
            'answer' => 'VAULT',
            'placeholder' => 'Enter 5-letter word'
        ),
        2 => array(
            'id' => 2,
            'title' => 'Room 2: Sequence Fuse Box',
            'story' => 'The cipher door slides open into the generator hallway. To power the elevator, you need to enter the missing voltage number in the pattern below.',
            'prompt' => 'Find the next number in the sequence:',
            'clue_box' => '3,  7,  15,  31,  ?',
            'answer' => '63',
            'placeholder' => 'Enter the missing number'
        ),
        3 => array(
            'id' => 3,
            'title' => 'Room 3: Logic Keypad',
            'story' => 'You reach the final vault door. It takes a 4-digit PIN based on four rules etched into the metal plate.',
            'prompt' => 'Use the 4 clues below to find the 4-digit PIN:',
            'clue_box' => "Digit 1: HTTP Not Found status code (404) divided by 101\nDigit 2: The only even prime number\nDigit 3: 2 raised to the 3rd power (2^3)\nDigit 4: Add the first three digits together, then add the two digits of that sum",
            'answer' => '4285',
            'placeholder' => 'Enter 4-digit PIN'
        )
    );
}

function getVaultHint($roomId, $hintTier) {
    $hints = array(
        1 => array(
            1 => 'Hint 1 (-10 pts): Shift each letter in YDXOW backward by 3 letters in the alphabet.',
            2 => 'Hint 2 (-20 pts): Y shifted back 3 is V, and D shifted back 3 is A.',
            3 => 'Hint 3 (-30 pts): The first four letters are V A U L. Shift W back 3 for the last letter.'
        ),
        2 => array(
            1 => 'Hint 1 (-10 pts): Look at how much is added each time: +4, +8, +16.',
            2 => 'Hint 2 (-20 pts): Each number is double the previous number plus 1 (n * 2 + 1).',
            3 => 'Hint 3 (-30 pts): Multiply 31 by 2 to get 62, then add 1.'
        ),
        3 => array(
            1 => 'Hint 1 (-10 pts): Digit 1 is 4 (since 404 / 101 = 4) and Digit 2 is 2.',
            2 => 'Hint 2 (-20 pts): Digit 3 is 8 (since 2 * 2 * 2 = 8). So the first three digits are 4, 2, 8.',
            3 => 'Hint 3 (-30 pts): 4 + 2 + 8 = 14, and 1 + 4 = 5. Combine all four digits.'
        )
    );

    if (isset($hints[$roomId]) && isset($hints[$roomId][$hintTier])) {
        return $hints[$roomId][$hintTier];
    }
    return '';
}

function getHintCost($hintTier) {
    $costs = array(1 => 10, 2 => 20, 3 => 30);
    return isset($costs[$hintTier]) ? $costs[$hintTier] : 0;
}

function loadLeaderboard() {
    if (!isset($_SESSION['leaderboard']) || !is_array($_SESSION['leaderboard'])) {
        $file = getDataFilePath('leaderboard.json');
        if (file_exists($file)) {
            $raw = @file_get_contents($file);
            $parsed = json_decode($raw, true);
            if (is_array($parsed)) {
                $_SESSION['leaderboard'] = $parsed;
            }
        }
    }
    if (empty($_SESSION['leaderboard'])) {
        $_SESSION['leaderboard'] = array(
            array(
                'username' => 'rajan',
                'score' => 280,
                'hints' => 1,
                'attempts' => 4,
                'time_sec' => 95,
                'date' => '2026-11-09'
            ),
            array(
                'username' => 'alex_gsu',
                'score' => 250,
                'hints' => 2,
                'attempts' => 5,
                'time_sec' => 142,
                'date' => '2026-11-09'
            )
        );
    }
    $board = $_SESSION['leaderboard'];
    usort($board, function ($a, $b) {
        if ($b['score'] !== $a['score']) {
            return $b['score'] - $a['score'];
        }
        return $a['time_sec'] - $b['time_sec'];
    });
    return $board;
}

function addLeaderboardEntry($entry) {
    $board = loadLeaderboard();
    $board[] = $entry;
    usort($board, function ($a, $b) {
        if ($b['score'] !== $a['score']) {
            return $b['score'] - $a['score'];
        }
        return $a['time_sec'] - $b['time_sec'];
    });
    $_SESSION['leaderboard'] = $board;
    $file = getDataFilePath('leaderboard.json');
    @file_put_contents($file, json_encode($board, JSON_PRETTY_PRINT));
}
?>
