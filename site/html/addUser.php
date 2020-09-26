<?php
require_once('dbManager.php');

session_start();
// If the user is not logged he will be redirected to the login page
if(!$_SESSION['logon']){
    header('Location: login.php');
}

// todo: redirect user if not admin

$username = $_POST['username'];
$password = $_POST['password'];
$isValid = isset($_POST['isValid']);
$selectedRole = $_POST['role'];

if (isset($_POST['addUser'])) {
    if (!empty($username) && !empty($password) && !empty($selectedRole)) {
        // Save user details
        $dbManager = new dbManager();
        //todo: password strength perhaps ?
        $dbManager->addUser($username, $password, $isValid, $selectedRole);

        //todo: better method ?
        header('Location: users.php');
    } else {
        // Invalid information passed to saveUser
        echo 'invalid';
    }
}

$dbManager = new dbManager();
$roles = $dbManager->findAllRoles();
?>

<html>
    <head>
        <title>Add user</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
                        <div class="row align-items-center">
                            <div class="col mt-4">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control" value="<?php if (!empty($username)) echo $username ?>" placeholder="Username">
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col mt-4">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" value="<?php if (!empty($password)) echo $password ?>" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
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
                                <select class="form-control" id="role" name="role">
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
                                <button type="submit" class="btn btn-secondary mt-4" formaction="/users.php">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>