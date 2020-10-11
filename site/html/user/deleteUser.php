<?php
require_once('../class/dbManager.php');
require_once('../class/identityManagement.php');

session_start();
// If the user is not logged he will be redirected to the login page
if(!$_SESSION['logon']){
    header('Location: ../login.php');
    exit();
}
// If the user is not an admin we cannot delete so he will be redirected
if (!IdentityManagement::isPageAllowed($_SESSION['role'])) {
    header('Location: ../inbox.php');
    exit();
}
// Check if the value id is passed to the page
if (!isset($_GET['id']) or empty($_GET['id'])) {
    die('Invalid arguments passed to the page');
}

try {
    $dbManager = new dbManager();
    $dbManager->deleteUser($_GET['id']);
} catch(PDOException $e) {
    die('Connection to the database failed');
}

header('Location: ../users.php');
