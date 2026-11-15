# the php vault

csc 4370 project 2 (topic 4: the php vault). a 3-room escape puzzle game built in php, html, and css (no javascript, no database).

live codd url: https://codd.cs.gsu.edu/~rsharma30/Sharma_PHPVault/index.php
demo login: `rajan` / `Vault123` (room answers: `VAULT`, `63`, `4285`)

## how to run locally
```
php -S localhost:8000
```
then open `http://localhost:8000/index.php` in your browser.

## features
- session login and registration (`includes/auth.php`) using `password_hash()` and `password_verify()`
- 3 puzzle rooms (`room.php` and `includes/vault_data.php`) checked via `POST` forms in php
- session leaderboard (`leaderboard.php`) sorted by highest score first and fastest time second using `usort()`
- light/dark theme preference saved in a 30-day cookie (`$_COOKIE['vault_theme']`)

## hint and attempt rule (ai feature)
- decision rule: `getVaultHint($roomId, $hintTier)` unlocks up to 3 tiered hints per room (-10, -20, and -30 pts), and `getWrongAttemptMessage($roomId, $roomAttempts, $rawGuess)` checks the room number, input format/length, and how many wrong tries the player has made in that room to return a specific error message.
- session data used: `$_SESSION['vault_run']['current_room']`, `$_SESSION['vault_run']['hints_used']`, and `$_SESSION['vault_run']['room_attempts']`.
- limitation: all hints and error messages come from fixed php rules for the 3 rooms rather than generating new puzzles dynamically, and each room caps out at 3 hints.

## test cases
- test 1 (normal run): log in as `rajan`, unlock hint 1 in room 1 (-10 pts, score 290), submit `VAULT`, `63`, and `4285` -> redirects to `results.php` with 290 pts and adds the run to `leaderboard.php`.
- test 2 (boundary / hint cap): click `Get Hint` 3 times in room 1 (score drops from 300 to 290, 270, 240), then click it a 4th time -> php keeps score at 240 pts and prints that all 3 hints are already unlocked.
- test 3 (invalid / empty input): submit an empty answer in `room.php` -> php shows an error without taking off 10 points; submit a 3-letter word `ABC` in room 1 -> takes off 10 points and warns that the passkey has 5 letters.
