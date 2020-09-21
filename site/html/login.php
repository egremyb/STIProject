
<?php
/*Login page for the website*/
try {

    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE,
                             PDO::ERRMODE_EXCEPTION);

    // Ask for the Users available in the database
    $users = $file_db->query('SELECT * FROM Users');

    // Check if the password is set and the username too
    if (isset($_POST['pass']) && isset($_POST['username'])) {
        // Check if the user sent by the form exists in the database
        foreach($users as $row) {
            var_dump($row);
            if ($_POST['pass'] == $row['password'] && $_POST['username'] == $row['username']) {
                // If the user isn't already logged a new session will start
                if (!session_id())
                    session_start();
                $_SESSION['logon'] = true;
                $_SESSION['id'] = $row['id'];
                // Close the connection with the database
                $file_db = null;
                // Go to the inbox
                header('Location: inbox.php');
                die();
            }
        }
    }
    $file_db = null;
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