<?php
require_once __DIR__ . '/includes/auth.php';
unset($_SESSION['user']);
unset($_SESSION['vault_run']);
header('Location: index.php');
exit;
?>
