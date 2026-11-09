# sharma_phpvault scrum log

rajan sharma
csc 4370
topic 4: the php vault

## daily updates

### nov 5
- done: set up repo structure, sketched the 3 wireframes, and planned out the session variables for the vault rooms.
- next: build register.php, login.php, and includes/auth.php.
- blockers: none.

### nov 6
- done: built includes/auth.php with password_hash() and password_verify(), plus register.php, login.php, logout.php, and index.php. added cookie theme toggle.
- next: write the 3 puzzle rooms and hint logic in includes/vault_data.php.
- blockers: none.

### nov 7
- done: wrote includes/vault_data.php with the 3 puzzle rooms (caesar cipher, number sequence, logic gates) and the tiered php hint function. started room.php.
- next: finish room.php, results.php, and leaderboard.php.
- blockers: noticed refreshing room.php in the browser re-sends the last POST form and deducts points twice.

### nov 8
- done: built results.php, leaderboard.php with usort(), and styled everything in css/style.css with dark/light theme variables and mobile media query.
- next: fix the form resubmit and hint cap bug in room.php and deploy to codd.
- blockers: need to add post/redirect/get in room.php.

### nov 9
- done: fixed the room.php form resubmit bug using header('Location: room.php') and capped hints at 3 per room. uploaded files to codd and verified all pages.
- next: record demo video before final deadline.
- blockers: none.
