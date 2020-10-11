<?php
require_once('class/dbManager.php');
session_start();
// If the user is not logged he will be redirected to the login page
if(!$_SESSION['logon']){
    header('Location: login.php');
    exit();
}

try {
    // Connection to the database
    $dbManager = new dbManager();
    // Find all messages for current user
    $messages = $dbManager->findAllMessagesFor($_SESSION['id']);
    $dbManager->closeConnection();
}
catch(PDOException $e) {
    die('Connection to the database failed');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <meta charset="UTF-8">
    <title>Inbox</title>
</head>
<body>
    <!-- Include the navigation bar of the site -->
    <?php require_once('fragments/NavBar.php')?>
    <!-- Page Content -->
    <div class="container">
        <div class="card border-0 shadow my-5">
            <div>
                <table class="table messages">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Sender</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Will show all messages
                    foreach($messages as $msg) {
                        echo <<<EOT
                    <tr>
                         <th>{$msg['date']}</th>
                         <th>{$msg['username']}</th>
                         <th>{$msg['subject']}</th>
                         <th>
                            <span class="actions">
                                <a href="../message/message.php?id={$msg['id']}"><span class="material-icons">reply</span></a>
                                <a href="../message/details.php?id={$msg['id']}&deleteForm=yes"><span class="material-icons">delete</span></a>
                                <a href="../message/details.php?id={$msg['id']}"><span class="material-icons">launch</span></a>
                            </span>
                        </th>
                    </tr>
EOT;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
