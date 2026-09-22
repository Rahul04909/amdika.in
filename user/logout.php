<?php
require_once '../includes/session_config.php';
session_unset();
session_destroy();
header("Location: ../index.php");
exit;
?>
