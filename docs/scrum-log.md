# sharma_phpvault scrum log

rajan sharma
csc 4370
topic 4: the php vault

## sprint schedule (nov 9 - nov 17)
- sprint 1 (day 1, nov 9): proposal, wireframes, and repo setup [done]
- sprint 2 (day 2, nov 10): session auth, login, register, logout, and home page [done]
- sprint 3 (days 3-4, nov 11-12): 3 puzzle rooms and php hint / attempt helpers [done]
- sprint 4 (days 5-6, nov 13-14): results, leaderboard, css, and bug fixes [done]
- sprint 5 (days 7-8, nov 15-16): live codd testing and presentation slides/video [upcoming]
- sprint 6 (day 9, nov 17): final review and icollege submission [upcoming]

## daily stand-up and sprint notes

### sprint 1 - nov 9 (day 1)
- done: finished proposal doc, sketched the 3 wireframes, planned out the `$_SESSION['vault_run']` keys, and initialized the github repo.
- next: build `includes/auth.php`, `register.php`, `login.php`, `logout.php`, and `index.php`.
- blockers: none.

### sprint 2 - nov 10 (day 2)
- done: built `includes/auth.php` with `password_hash()` and `password_verify()`, plus `register.php`, `login.php`, `logout.php`, and `index.php`. added cookie toggle for light/dark theme.
- next: write the 3 puzzle rooms and hint logic in `includes/vault_data.php`.
- blockers: none.

### sprint 3 - nov 11-12 (days 3-4)
- done: wrote `includes/vault_data.php` with the 3 puzzle rooms (shift cipher, number sequence, 4-digit keypad) and the tiered php hint helper. built the first version of `room.php`.
- next: build `results.php`, `leaderboard.php`, and `css/style.css`.
- blockers: noticed refreshing `room.php` in the browser re-sends the last POST form and deducts points a second time.

### sprint 4 - nov 13-14 (days 5-6)
- done: built `results.php`, `leaderboard.php` with `usort()`, and `css/style.css` (simplified the layout to a clean single-column light theme). fixed the form resubmit bug in `room.php` using `header('Location: room.php')`, capped hints at 3 per room, and added per-room attempt tracking (`room_attempts`) with specific wrong-answer feedback in `getWrongAttemptMessage()`. ran the 3 test cases and updated `README.md`.
- next: do a full test pass on codd and prepare presentation slides/video in sprint 5.
- blockers: none.
