# sharma_phpvault scrum log

rajan sharma
csc 4370
topic 4: the php vault

## sprint schedule (nov 9 - nov 17)
- sprint 1 (day 1, nov 9): proposal, wireframes, and repo setup [done]
- sprint 2 (day 2, nov 10): session auth, login, register, logout, and home page [done]
- sprint 3 (days 3-4, nov 11-12): 3 puzzle rooms and php hint / attempt helpers [done]
- sprint 4 (days 5-6, nov 13-14): results, leaderboard, css, and bug fixes [done]
- sprint 5 (days 7-8, nov 15-16): live codd testing and presentation slides [done]
- sprint 6 (day 9, nov 17): final code review, qa prep, and submission package [done]

## program design and pseudocode summary

### session and cookie structure
- `$_SESSION['user']`: logged-in username string
- `$_SESSION['vault_run']`: associative array with `current_room` (1..3), `score` (starts at 300), `attempts`, `room_attempts` (per-room count), `hints_used` (per-room tier 0..3), `start_time`, `completed`, and `saved_to_board`
- `$_SESSION['leaderboard']`: array of completed run records sorted by `score` DESC then `time_sec` ASC
- `$_COOKIE['vault_theme']`: `'light'` or `'dark'` (30-day expiry)

### core room.php pseudocode
```
requireLogin()
if POST reset_run -> startNewVaultRun(), redirect to room.php
if vault_run.completed is true -> redirect to results.php
currentRoom = vault_run.current_room

if POST request_hint:
    used = vault_run.hints_used[currentRoom]
    if used >= 3:
        set feedback "All 3 hints unlocked (0 pts deducted)"
    else:
        nextTier = used + 1
        cost = getHintCost(nextTier) // 10, 20, or 30
        vault_run.hints_used[currentRoom] = nextTier
        vault_run.score = max(0, vault_run.score - cost)
    redirect to room.php (Post/Redirect/Get)

if POST submit_answer:
    guess = trim(POST answer)
    if guess is empty:
        set error "Please enter an answer (no points deducted)"
    else:
        increment vault_run.attempts and vault_run.room_attempts[currentRoom]
        if strtoupper(guess) == room.answer:
            if currentRoom < 3:
                vault_run.current_room = currentRoom + 1
                redirect to room.php
            else:
                vault_run.completed = true
                vault_run.end_time = time()
                redirect to results.php
        else:
            vault_run.score = max(0, vault_run.score - 10)
            set error from getWrongAttemptMessage(currentRoom, roomAttempts, guess)
    redirect to room.php
```

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
- next: deploy latest changes to codd and prepare presentation slides/video in sprint 5.
- blockers: none.

### sprint 5 - nov 15-16 (days 7-8)
- done: deployed all updated php and css files to codd (`~rsharma30/Sharma_PHPVault/`), tested full playthrough and edge cases live on codd, and put together the presentation slides and code walkthrough notes.
- next: final check of q&a defense answers, record walkthrough video, and submit on icollege.
- blockers: none.

### sprint 6 - nov 17 (day 9)
- done: added program design pseudocode and final sprint review notes to `docs/scrum-log.md`, verified all links (codd, github, readme), and prepared the final submission text file.
- sprint retrospective: breaking the 9 days into 6 small sprints helped catch the POST refresh bug in sprint 3 before building the results and leaderboard pages in sprint 4.
- blockers: none.
