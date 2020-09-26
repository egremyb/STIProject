<?php
require_once('dbManager.php');
/*Login page for the website*/
try {
    $dbManager = new dbManager();

    // Check if the password is set and the username too
    if (isset($_POST['pass']) && isset($_POST['username'])) {
        // Check if the user sent by the form exists in the database
        $user = $dbManager->findUserByUsernamePassword($_POST['username'], $_POST['pass']);
        if($user != false) {
            // If the user isn't already logged a new session will start
            if (!session_id())
                session_start();
            $_SESSION['logon'] = true;
            $_SESSION['id'] = $user['id'];
            // Close the connection with the database
            $dbManager->closeConnection();
            // Go to the inbox
            header('Location: inbox.php');
            die();
        }
    }
    $dbManager->closeConnection();
}
catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
}
?>

<form action="/login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="pass">Password:</label>
    <input type="text" id="pass" name="pass"><br><br>
    <input type="submit" value="Login">
</form>