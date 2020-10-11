<?php
require_once('../class/dbManager.php');
require_once('../class/identityManagement.php');

session_start();
// If the user is not logged he will be redirected to the login page
if(!$_SESSION['logon']){
    header('Location: ../login.php');
    exit();
}

// If the user is not administrator we redirect him
if (!IdentityManagement::isPageAllowed($_SESSION['role'])) {
    header('Location: ../inbox.php');
    exit();
}

// To not lose all the information added to the form
$username = $_POST['username'];
$password = $_POST['password'];
$isValid = isset($_POST['isValid']);
$selectedRole = $_POST['role'];

try {
    $dbManager = new dbManager();

    if (isset($_POST['addUser'])) {
        // If the form is filled
        if (!empty($username) && !empty($password) && !empty($selectedRole)) {
            // Save user details
            // The username has to be unique so if the user is found in the database show an error message
            $foundUser = $dbManager->findUserByUsername($username);
            if ($foundUser) {
                $error = "Username not available";
            } else {
                $dbManager->addUser($username, $password, $isValid, $selectedRole);

                header('Location: ../users.php');
                exit();
            }
        } else {
            // Invalid information passed to saveUser
            $error = 'Invalid information to create a user';
        }
    }

    $roles = $dbManager->findAllRoles();
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
        <title>Add user</title>
    </head>
    <body>
        <form action="addUser.php" method="post">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-8 col-xl-6">
                        <div class="row">
                            <div class="col text-center">
                                <h1>Create a new user</h1>
                            </div>
                        </div>
                        <?php
                        if (isset($error) && !empty($error)) {
                            echo '<div class="row">
                                    <div class="col text-center">
                                        <p class="error">' . $error . '
                                    </div>
                                 </div>';
                        }
                        ?>
                        <div class="row align-items-center">
                            <div class="col mt-4">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control" required value="<?php if (!empty($username)) echo $username ?>" placeholder="Username">
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col mt-4">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required value="<?php if (!empty($password)) echo $password ?>" placeholder="Password">
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="isValid" name="isValid" <?php if ($isValid) echo 'checked'?> >
                                    <label class="form-check-label" for="isValid">Enable account</label>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <?php
                                    foreach ($roles as $role) {
                                        $opt = '<option class="form-control" value="' . $role['id'] . '"';
                                        if ($role['id'] == $selectedRole)
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
                                <button type="submit" name="addUser" class="btn btn-primary mt-4">Submit</button>
                                <button type="submit" class="btn btn-secondary mt-4" formaction="../users.php" formnovalidate>Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>