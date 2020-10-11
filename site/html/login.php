<!-- Login page for the website -->
<?php
require_once('class/dbManager.php');
try {
    // Create the connection to the database
    $dbManager = new dbManager();

    // Check if the password is set and the username too
    if (isset($_POST['pass']) && isset($_POST['username'])) {
        // Invalid credentials by default
        $error = "Invalid username / password";

        // Check if the user sent by the form exists in the database
        $user = $dbManager->findUserByUsername($_POST['username']);
        // If the user does not exist the database send the false value
        if($user != false){
            // Check that the password entered is the same as the user stored
            if(password_verify($_POST['pass'], $user['password'])){
                // Check that the account is active
                if ($user['isValid'] !== 'yes') {
                    $error = "Account is not active";
                } else {
                    // If the user isn't already logged a new session will start
                    if (!session_id())
                        session_start();
                    // Set different Sessions values for the user
                    $_SESSION['logon'] = true;
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['role'] = $dbManager->getRoleName($user['role']);
                    // Close the connection with the database
                    $dbManager->closeConnection();
                    // Go to the inbox
                    header('Location: inbox.php');
                    die();
                }
            }
        }
    }
}
catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="css/signin.css">
        <link rel="stylesheet" href="css/common.css">

        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row no-gutter">
                <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
                <div class="col-md-8 col-lg-6">
                    <div class="login d-flex align-items-center py-5">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-9 col-lg-8 mx-auto">
                                    <h3 class="login-heading mb-4">Welcome back!</h3>
                                    <?php
                                        if (isset($error) && !empty($error)) {
                                            echo '<p class="error">' . $error . '</p>';
                                        }
                                    ?>
                                    <form action="./login.php" method="post">
                                        <div class="form-label-group">
                                            <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
                                            <label for="username">Username</label>
                                        </div>

                                        <div class="form-label-group">
                                            <input type="password" id="pass" name="pass" class="form-control" placeholder="Password" required>
                                            <label for="pass">Password</label>
                                        </div>
                                        <button class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit" value="Login">Sign in</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>