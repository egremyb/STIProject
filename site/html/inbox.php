<?php
require_once('dbManager.php');
session_start();
// If the user is not logged he will be redirected to the login page
if(!$_SESSION['logon']){
    header('Location: login.php');
    exit();
}
const ACTIONS = '<span class="actions">
                    <a href="#"><span class="material-icons">reply</span></a>
                    <a href="#"><span class="material-icons">delete</span></a>
                    <a href="#"><span class="material-icons">launch</span></a>
                </span>';

try {
    $dbManager = new dbManager();

    $messages = $dbManager->findAllMessagesFor($_SESSION['id']);
}
catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
}


?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light static-top mb-5 shadow">
    <div class="container">
        <a class="navbar-brand" href="#">Inbox</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="inbox.php">Inbox
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="message.php">New Message</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="users.php">User management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

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
                foreach($messages as $msg) {
                    echo <<<EOT
                <tr>
                     <th>{$msg['date']}</th>
                     <th>{$msg['username']}</th>
                     <th>{$msg['subject']}</th>
                     <th>
                        <span class="actions">
                            <a href="message.php?id={$msg['id']}"><span class="material-icons">reply</span></a>
                            <a href="details.php?id={$msg['id']}&deleteForm=yes"><span class="material-icons">delete</span></a>
                            <a href="details.php?id={$msg['id']}"><span class="material-icons">launch</span></a>
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

