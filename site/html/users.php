<?php
require_once('class/dbManager.php');
require_once('class/identityManagement.php');
try{
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
    $users = $dbManager->findAllUsers($paginationStart, $limit);

    $allUsers = $dbManager->countAllUsers();

    // Calculate total pages
    $totoalPages = ceil($allUsers[0]['count(id)'] / $limit);

    // Prev + Next
    $prev = $page - 1;
    $next = $page + 1;

    // If the user is not an admin he cannot see the page
    if (!IdentityManagement::isPageAllowed($_SESSION['role'])) {
        $dbManager->closeConnection();
        header('Location: inbox.php');
        exit();
    }


    $dbManager->closeConnection();
} catch(PDOException $e) {
    die('Connection to the database failed');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <meta charset="UTF-8">
        <title>User Management</title>
    </head>
    <body>
    <?php require_once('fragments/navBar.php')?>

        <div class="container">
            <?php require_once('fragments/paginationSelector.php') ?>
            <div class="card border-0 shadow my-5">
                <div>
                    <table class="table messages">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Account valid</th>
                                <th scope="col">Role</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($users as $user) {
                            echo <<<EOT
                                <tr>
                                     <th>{$user['username']}</th>
                                     <th>{$user['isValid']}</th>
                                     <th>{$user['rolename']}</th>
                                     <th>
                                        <span class="actions">
                                            <a href="user/editUser.php?id={$user['id']}"><span class="material-icons">edit</span></a>
                                            <a href="user/deleteUser.php?id={$user['id']}"><span class="material-icons">delete</span></a>
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
            <a href="user/addUser.php" class="btn btn-primary mt-4">Add user</a>
        </div>
    </body>
</html>


