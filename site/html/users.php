<?php
require_once('class/dbManager.php');
require_once('class/identityManagement.php');
require_once('class/utils.php');
try{
    $dbManager = new dbManager();
    session_start();
    IdentityManagement::isSessionValid($_SESSION, $dbManager);

    // If the user is not an admin he cannot see the page
    if (!IdentityManagement::isPageAllowed($_SESSION['role'])) {
        $dbManager->closeConnection();
        header('Location: inbox.php');
        exit();
    }
    // Initialize the value for the pagination
    $pageInit = Utils::initPagination($_POST['records-limit'], $_GET['page']);

    // Find all users for the page
    $users = $dbManager->findAllUsers($pageInit['paginationStart'], $pageInit['limit']);
    // Count all the users in the database
    $allUsers = $dbManager->countAllUsers();

    // Calculate total pages
    $totalPages = ceil($allUsers[0]['count(id)'] / $pageInit["limit"]);

    // Prev + Next
    $prev = $pageInit['page'] - 1;
    $next = $pageInit['page'] + 1;

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
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="css/common.css">

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
                            if($user['id'] != $_SESSION['id']) {
                                echo <<<EOT
                                <tr>
                                     <th>{$user['username']}</th>
                                     <th>{$user['isValid']}</th>
                                     <th>{$user['rolename']}</th>
                                     <th>
                                        <span class="actions">
                                            <form action="user/editUser.php" method="post" class="inline">
                                                <input hidden name="id" value="{$user['id']}">
                                                <button class="btn-appearance-none">
                                                    <span class="material-icons">edit</span>
                                                </button>
                                            </form>
                                            <form action="user/deleteUser.php" method="post" class="inline">
                                                <input hidden name="id" value="{$user['id']}">
                                                <button class="btn-appearance-none">
                                                    <span class="material-icons">delete</span>
                                                </button>
                                            </form>
                                        </span>
                                    </th>
                                </tr>
EOT;
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php require_once('fragments/pagination.php'); ?>
                </div>
            </div>
            <a href="user/addUser.php" class="btn btn-primary mt-4">Add user</a>
        </div>
        <script>
            $(document).ready(function () {
                $('#records-limit').change(function () {
                    $('#paginationForm').submit();
                })
            });
        </script>
    </body>
</html>


