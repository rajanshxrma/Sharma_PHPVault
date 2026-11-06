# the php vault

csc 4370 project 2 (topic 04: the php vault). a server-side escape room puzzle game built with php, html5, and css3 (no javascript, no database).

## features
- session-based registration and login using `password_hash()` and `password_verify()`
- 3 sequential puzzle rooms (caesar cipher terminal, sequence fuse box, logic gate keypad) validated via `POST` forms in php
- tiered php rule-based hint system (-10, -20, -30 pts)
- session leaderboard sorting completed runs by score and time
- cookie preference for light/dark vault theme

## how to run
run locally with the built-in php server:
```
php -S localhost:8000
```
then open `http://localhost:8000/index.php` in your browser.

live codd url: `https://codd.cs.gsu.edu/~rsharma30/Sharma_PHPVault/index.php`
