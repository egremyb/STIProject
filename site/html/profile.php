<?php
require_once('class/dbManager.php');
require_once('class/identityManagement.php');

try {
    // Connection to the database
    $dbManager = new dbManager();
    session_start();
    IdentityManagement::isSessionValid($_SESSION, $dbManager,false);

    // Find user in the database
    $user = $dbManager->findUserByID($_SESSION['id']);

    // Check form was submitted
    if (isset($_POST['savePassword'])) {
        // Check password and confirmation are filled
        if (empty($_POST['password']) || empty($_POST['confirm_password'])) {
            $error = 'Please fill the required fields';
        // Check password and confirm password are equal
        } else if ($_POST['password'] !== $_POST['confirm_password']) {
            $error = "Passwords are different";
        // Check password is strong enough
        } else if (!IdentityManagement::isPasswordStrong($_POST['password'])) {
            $error = 'Password should contain at least 8 characters, one upper case letter, one number, and one special character';
        } else {
            // Save the new password in the database
            $dbManager->saveUserPassword($_SESSION['id'], $_POST['password']);
            $message = 'Password has been saved!';
        }
    }

    $dbManager->closeConnection();
} catch(PDOException $e) {
    die('Connection to the database failed');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/common.css">

    <meta charset="UTF-8">
    <title>Profile</title>
</head>
    <body>
    <?php require_once('fragments/navBar.php')?>

    <form action="profile.php" method="post">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-8 col-xl-6">
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
                    <div class="row align-items-center">
                        <div class="col mt-4">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" class="form-control" readonly value="<?php echo $user['username'] ?>" placeholder="Username">
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col mt-4">
                            <label for="username">Role</label>
                            <input type="text" id="role" name="role" class="form-control" readonly value="<?php echo $_SESSION['role'] ?>" placeholder="Role">
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col mt-4">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required placeholder="Password">
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col mt-4">
                            <label for="confirm_password">Confirm password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required placeholder="Confirm password">
                        </div>
                    </div>
                    <div class="row justify-content-start mt-4">
                        <div class="col">
                            <button type="submit" name="savePassword" class="btn btn-primary mt-4">Change password</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>


