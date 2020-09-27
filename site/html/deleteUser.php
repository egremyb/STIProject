<?php
require_once('dbManager.php');
require_once('identityManagement.php');

session_start();
// If the user is not logged he will be redirected to the login page
if(!$_SESSION['logon']){
    header('Location: login.php');
}

if (!IdentityManagement::isPageAllowed($_SESSION['role'])) {
    header('Location: inbox.php');
}

if (!isset($_GET['id']) or empty($_GET['id'])) {
    die('Invalid arguments passed to the page');
}

$dbManager = new dbManager();
$dbManager->deleteUser($_GET['id']);
header('Location: users.php');
