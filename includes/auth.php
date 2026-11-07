<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getDataFilePath($filename) {
    $dir = __DIR__ . '/../data';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    return $dir . '/' . $filename;
}

function loadUsers() {
    if (!isset($_SESSION['users']) || !is_array($_SESSION['users'])) {
        $file = getDataFilePath('users.json');
        if (file_exists($file)) {
            $raw = @file_get_contents($file);
            $parsed = json_decode($raw, true);
            if (is_array($parsed)) {
                $_SESSION['users'] = $parsed;
            }
        }
    }
    if (empty($_SESSION['users'])) {
        $_SESSION['users'] = array(
            'rajan' => array(
                'username' => 'rajan',
                'password_hash' => password_hash('Vault123', PASSWORD_DEFAULT),
                'created_at' => '2026-11-09'
            )
        );
        saveUsers($_SESSION['users']);
    }
    return $_SESSION['users'];
}

function saveUsers($users) {
    $_SESSION['users'] = $users;
    $file = getDataFilePath('users.json');
    @file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

function registerUser($username, $password) {
    $users = loadUsers();
    $key = strtolower($username);
    if (isset($users[$key])) {
        return array('ok' => false, 'msg' => 'That username is already taken.');
    }
    $users[$key] = array(
        'username' => $username,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d')
    );
    saveUsers($users);
    return array('ok' => true, 'msg' => 'Account created. You can log in now.');
}

function verifyUserLogin($username, $password) {
    $users = loadUsers();
    $key = strtolower($username);
    if (!isset($users[$key])) {
        return false;
    }
    if (password_verify($password, $users[$key]['password_hash'])) {
        return $users[$key]['username'];
    }
    return false;
}

function startNewVaultRun() {
    $_SESSION['vault_run'] = array(
        'current_room' => 1,
        'score' => 300,
        'attempts' => 0,
        'hints_used' => array(1 => 0, 2 => 0, 3 => 0),
        'start_time' => time(),
        'completed' => false,
        'saved_to_board' => false
    );
}

function requireLogin() {
    if (empty($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
    if (!isset($_SESSION['vault_run'])) {
        startNewVaultRun();
    }
}

function getThemeClass() {
    if (isset($_POST['toggle_theme'])) {
        $current = isset($_COOKIE['vault_theme']) ? $_COOKIE['vault_theme'] : 'light';
        $next = ($current === 'dark') ? 'light' : 'dark';
        setcookie('vault_theme', $next, time() + (86400 * 30), '/');
        $_COOKIE['vault_theme'] = $next;
    }
    $theme = isset($_COOKIE['vault_theme']) ? $_COOKIE['vault_theme'] : 'light';
    return ($theme === 'dark') ? 'theme-dark' : 'theme-light';
}
?>
