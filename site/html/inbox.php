<?php
require_once('dbManager.php');
session_start();
// If the user is not logged he will be redirected to the login page
if(!$_SESSION['logon']){
    header('Location: login.php');
}
const ACTIONS = '<span class="actions">
                    <a href="#"><span class="material-icons">reply</span></a>
                    <a href="#"><span class="material-icons">delete</span></a>
                    <a href="#"><span class="material-icons">launch</span></a>
                </span>';

try {
    $dbManager = new dbManager();

    $messages = $dbManager->findAllMessages();
}
catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
}


?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

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
<form action="/message.php">
    <button type="submit">New message</button>
</form>

