<?php
require_once 'includes/session_config.php';

if (!isset($_SESSION['counter'])) {
    $_SESSION['counter'] = 0;
}
$_SESSION['counter']++;

echo "<h1>Session Test</h1>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Counter: " . $_SESSION['counter'] . "</p>";
echo "<p>Reload this page. If the counter increases, sessions are working.</p>";
echo "<p>Server Path: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Cookie Params: <pre>" . print_r(session_get_cookie_params(), true) . "</pre></p>";
?>
