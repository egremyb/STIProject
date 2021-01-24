<!-- Login page for the website -->
<?php
require_once('class/dbManager.php');
require_once('class/identityManagement.php');
$lifetime = 0;
$path = '/';
$samesite = 'strict';
$domain = '';
$secure = false;
$httpOnly = true;
session_set_cookie_params($lifetime, $path.'; samesite='.$samesite, $domain, $secure, $httpOnly);
session_start();
// Redirect user to inbox if already logged in
if (isset($_SESSION['logon'])) {
    header('Location: inbox.php');
    die();
}
try {
    // Create the connection to the database
    $dbManager = new dbManager();
    // Check if the password is set and the username too
    if (isset($_POST['pass']) && isset($_POST['username'])) {
        // Invalid credentials by default
        $error = "Invalid username / password";

        if (!checkCaptcha()) {
            $error = "Invalid Captcha";
        } else {
            // Check if the user sent by the form exists in the database
            $user = $dbManager->findUserByUsername($_POST['username']);
            // If the user does not exist the database send the false value
            if($user != false) {
                // Check that the password entered is the same as the user stored
                if(password_verify($_POST['pass'], $user['password'])){
                    // Check that the account is active
                    if ($user['isValid'] !== 'yes') {
                        $error = "Account is not active";
                    } else {
                        // Set different Sessions values for the user
                        $_SESSION['logon'] = true;
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['role'] = $dbManager->getRoleName($user['role']);
                        $_SESSION['token'] = IdentityManagement::generateNewSessionToken();
                        // Close the connection with the database
                        $dbManager->closeConnection();
                        // Go to the inbox
                        header('Location: inbox.php');
                        die();
                    }
                }
            } else {
                // Still verify the password to prevent user enumeration through response timing
                // Use a random keyword hash to compare
                password_verify($_POST['pass'], '$2y$10$bytddRuEXIru/YCb.jtXNep4.Z4r7ZInXdLDwp8s6Vko1157xTagq');
            }
        }
    }
}
catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
}
/**
 * https://www.knowband.com/blog/tips/integrate-google-recaptcha-php/
 * @return false
 */
function checkCaptcha() {
    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret = '6LcobTQaAAAAAPvqMtl3r2HUIEIttYFzSa_VQk2B';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        return $responseData->success;
    } else return false;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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
                                        <div class="g-recaptcha" data-sitekey="6LcobTQaAAAAALpGRu55GiRfOHV_iWm3nB1LKoQq"></div>
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
    <script src="https://www.google.com/recaptcha/api.js?render=6LcobTQaAAAAALpGRu55GiRfOHV_iWm3nB1LKoQq"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6LcobTQaAAAAALpGRu55GiRfOHV_iWm3nB1LKoQq', {action: 'homepage'}).then(function(token) {
                document.getElementById('recaptchaResponse').value = token
            });
        });
    </script>
</html>