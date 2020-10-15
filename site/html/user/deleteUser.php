<?php
require_once('../class/dbManager.php');
require_once('../class/identityManagement.php');
try {
    $dbManager = new dbManager();
    session_start();
    IdentityManagement::isSessionValid($_SESSION, $dbManager);
    // If the user is not an admin we cannot delete so he will be redirected
    if (!IdentityManagement::isPageAllowed($_SESSION['role'])) {
        $dbManager->closeConnection();
        header('Location: ../inbox.php');
        exit();
    }
    // Check if the value id is passed to the page
    if (!isset($_GET['id']) or empty($_GET['id'])) {
        $dbManager->closeConnection();
        die('Invalid arguments passed to the page');
    }

    $dbManager->deleteUser($_GET['id']);
} catch(PDOException $e) {
    die('Connection to the database failed');
}

header('Location: ../users.php');
