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

$dbManager = new dbManager();
$users = $dbManager->findAllUsers();
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light static-top mb-5 shadow">
    <div class="container">
        <a class="navbar-brand" href="#">User management</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="inbox.php">Inbox</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="message.php">New Message</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#">User management
                    <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

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
                //todo: Add message if $users is null or empty
                foreach($users as $user) {
                    echo <<<EOT
                        <tr>
                             <th>{$user['username']}</th>
                             <th>{$user['isValid']}</th>
                             <th>{$user['rolename']}</th>
                             <th>
                                <span class="actions">
                                    <a href="editUser.php?id={$user['id']}"><span class="material-icons">edit</span></a>
                                    <a href="deleteUser.php?id={$user['id']}"><span class="material-icons">delete</span></a>
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
    <a href="addUser.php">Add user<span class="material-icons">launch</span></a>
</div>


