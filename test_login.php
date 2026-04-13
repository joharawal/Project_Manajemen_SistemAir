<?php
session_start();
$_SESSION['user'] = 'admin'; // Assume 'admin' is a user
require 'login/index.php';
?>
