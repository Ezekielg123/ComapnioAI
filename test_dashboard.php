<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$_SESSION['user_id'] = 1;
$_SESSION['role_name'] = 'Elderly';
$_SESSION['first_name'] = 'Hari';

$_SERVER['PHP_SELF'] = '/elderly/dashboard.php';

chdir('elderly');

ob_start();
require_once 'dashboard.php';
$output = ob_get_clean();

echo "\n--- SUCCESS! NO FATAL ERRORS ---\n";
echo "Output length: " . strlen($output) . " bytes\n";
?>