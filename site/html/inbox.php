<?php
require_once('class/dbManager.php');
require_once('class/identityManagement.php');
try {
    // Connection to the database
    $dbManager = new dbManager();
    session_start();
    IdentityManagement::isSessionValid($_SESSION, $dbManager);

    if(isset($_POST['records-limit'])){
        $_SESSION['records-limit'] = $_POST['records-limit'];
    }

    $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 5;
    $page = (isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 1;
    $paginationStart = ($page - 1) * $limit;
    // Find all messages for current user
    $messages = $dbManager->findAllMessagesForUser($_SESSION['id'], $paginationStart, $limit);

    $allMessages = $dbManager->countAllMessagesForUser($_SESSION['id']);

    // Calculate total pages
    $totalPages = ceil($allMessages[0]['count(m.id)'] / $limit);

    // Prev + Next
    $prev = $page - 1;
    $next = $page + 1;

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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <meta charset="UTF-8">
    <title>Inbox</title>
</head>
<body>
    <!-- Include the navigation bar of the site -->
    <?php require_once('fragments/navBar.php')?>
    <!-- Page Content -->
    <div class="container">
        <?php require_once('fragments/paginationSelector.php') ?>
        <div class="card border-0 shadow my-5">
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
                <?php require_once('fragments/pagination.php'); ?>
        </div>
    </div>

    <!-- jQuery + Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#records-limit').change(function () {
                $('form').submit();
            })
        });
    </script>
</body>
</html>
