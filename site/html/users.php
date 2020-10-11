<?php
require_once('class/dbManager.php');
require_once('class/identityManagement.php');
try{
    $dbManager = new dbManager();
    session_start();
    IdentityManagement::isSessionValid($_SESSION, $dbManager, false);

    // If the user is not an admin he cannot see the page
    if (!IdentityManagement::isPageAllowed($_SESSION['role'])) {
        $dbManager->closeConnection();
        header('Location: inbox.php');
        exit();
    }

    $users = $dbManager->findAllUsers();
    $dbManager->closeConnection();
} catch(PDOException $e) {
    die('Connection to the database failed');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <meta charset="UTF-8">
        <title>User Management</title>
    </head>
    <body>
    <?php require_once('fragments/NavBar.php')?>

        <div class="container">
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
                </div>
            </div>
            <a href="user/addUser.php" class="btn btn-primary mt-4">Add user</a>
        </div>
    </body>
</html>


