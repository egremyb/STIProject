<?php
require_once('class/dbManager.php');
require_once('class/identityManagement.php');

session_start();
// If the user is not logged he will be redirected to the login page
if(!$_SESSION['logon']){
    header('Location: login.php');
    exit();
}

$dbManager = new dbManager();
$user = $dbManager->findUserByID($_SESSION['id']);

// If form was submitted
if (isset($_POST['savePassword'])) {
    // If password and confirmation are filled
    if (isset($_POST['password']) && isset($_POST['confirm_password'])) {
        // If password is valid
        if (!empty($_POST['password'])) {
            if ($_POST['password'] === $_POST['confirm_password']) {
                $dbManager->saveUserPassword($_SESSION['id'], $_POST['password']);
                $message = 'Password has been saved!';
            } else {
                $error = "Passwords are different";
            }
        } else {
            $error = 'Password cannot be empty';
        }
    } else {
        $error = 'Please fill the required fields';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/common.css">

    <meta charset="UTF-8">
    <title>Profile</title>
</head>
    <body>
    <?php require_once('fragments/NavBar.php')?>

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


