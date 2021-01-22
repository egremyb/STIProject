<?php
require_once('../class/dbManager.php');
require_once('../class/identityManagement.php');
try {
    $dbManager = new dbManager();
    session_start();
    IdentityManagement::isSessionValid($_SESSION, $dbManager);

    if (!IdentityManagement::isPageAllowed($_SESSION['role'])) {
        $dbManager->closeConnection();
        header('Location: ../inbox.php');
        exit();
    }

    // Check if id is sent with a get
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    }

    // If the user desired to change th user
    if (isset($_POST['saveUser']) && isset($_POST['token']) &&
        IdentityManagement::isTokenValid($_SESSION, $_POST['token'])) {
        if (isset($_POST['id']) && isset($_POST['role'])) {
            $id = $_POST['id'];

            // If checkbox is checked, $_POST var is set
            $isValid = isset($_POST['isValid']);

            // Check selected role
            if ($_POST['role'] != 0 || $_POST['role'] != 1) {
                $error = "Invalid role selected";
            } else {
                // Save user details
                // Save password if set
                if (isset($_POST['password']) && !empty($_POST['password'])) {
                    if (IdentityManagement::isPasswordStrong($_POST['password'])) {
                        $dbManager->saveUserPassword($id, $_POST['password']);
                    } else {
                        $error = 'Password should contain at least 8 characters, one upper case letter, one number, and one special character';
                    }
                }
            }

            if (empty($error)) {
                $dbManager->saveUserDetails($id, $isValid, $_POST['role']);
                $message = 'User information saved';
            }
        } else {
            // Invalid information passed to saveUser
            $error = 'Cannot edit user';
        }
    }

    // If the id is not set the page will die
    if (empty($id)) {
        $dbManager->closeConnection();
        die('Invalid arguments passed to the page');
    } else {
        $user = $dbManager->findUserByID($id);
        $roles = $dbManager->findAllRoles();
    }
} catch(PDOException $e) {
    die('Connection to the database failed');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="../css/common.css">

        <meta charset="UTF-8">
        <title>Edit user</title>
    </head>
    <body>
        <form method="post">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-8 col-xl-6">
                        <div class="row">
                            <div class="col text-center">
                                <h1>Edit user information</h1>
                            </div>
                        </div>
                        <?php
                        if (isset($error) && !empty($error)) {
                            echo '<div class="row">
                                    <div class="col text-center">
                                        <p class="error">' . $error . '
                                    </div>
                                 </div>';
                        } else if (isset($message) && !empty($message)) {
                            echo '<div class="row">
                                    <div class="col text-center">
                                        <p class="message">' . $message . '
                                    </div>
                                 </div>';
                        }
                        ?>
                        <input type="hidden" readonly name="token" value="<?php echo $_SESSION['token'] ?>" />
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
                                <button type="submit" class="btn btn-secondary mt-4" formaction="../users.php">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>
