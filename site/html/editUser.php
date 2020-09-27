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

if (isset($_POST['saveUser'])) {
    if (isset($_POST['id']) && isset($_POST['role'])) {
        // If checkbox is checked, $_POST var is set
        $isValid = isset($_POST['isValid']);

        // Save user details
        $dbManager = new dbManager();
        $dbManager->saveUserDetails($_POST['id'], $isValid, $_POST['role']);

        // Save password if set //todo: password strength perhaps ?
        if (isset($_POST['password']) && strlen($_POST['password']) > 2) {
            $dbManager->saveUserPassword($_POST['id'], $_POST['password']);
        }

        //todo: better method ?
        header('Location: editUser.php?id=' . $_POST['id']);
    } else {
        // Invalid information passed to saveUser
        echo 'invalid';
    }
} else if (!isset($_GET['id'])) {
    die('Invalid arguments passed to the page');
} else {
    $dbManager = new dbManager();

    $user = $dbManager->findUserByID($_GET['id']);
    $roles = $dbManager->findAllRoles();
}
?>

<html>
    <head>
        <title>Edit user</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    </head>
    <body>
        <form action="editUser.php" method="post">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-8 col-xl-6">
                        <div class="row">
                            <div class="col text-center">
                                <h1>Edit user information</h1>
                            </div>
                        </div>
                        <input type="hidden" readonly name="id" value="<?php echo $user['id'] ?>" />
                        <div class="row align-items-center">
                            <div class="col mt-4">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" readonly class="form-control" placeholder="Username" value="<?php echo $user['username']?>" >
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col mt-4">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="isValid" name="isValid" <?php echo $user['isValid'] == 'yes' ? 'checked' : ''?> >
                                    <label class="form-check-label" for="isValid">Enable account</label>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role">
                                    <?php
                                    foreach ($roles as $role) {
                                        $opt = '<option class="form-control" value="' . $role['id'] . '"';
                                        if ($role['id'] == $user['role'])
                                            $opt .= ' selected';
                                        $opt .= '>' . $role['name'] . '</option>';
                                        echo $opt;
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row justify-content-start mt-4">
                            <div class="col">
                                <button type="submit" name="saveUser" class="btn btn-primary mt-4">Submit</button>
                                <button type="submit" class="btn btn-secondary mt-4" formaction="/users.php">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>