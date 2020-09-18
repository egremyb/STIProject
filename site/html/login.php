/**
This is the page for the login of the application
**/
<?php
// Set default timezone
date_default_timezone_set('UTC');

try {
    /**************************************
     * Create databases and                *
     * open connections                    *
     **************************************/

    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE,
                             PDO::ERRMODE_EXCEPTION);

    $users =  $file_db->query('SELECT * FROM Users');
}
catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
}

if (isset($_POST['pass']) && isset($_POST['username'])) {
    foreach($users as $row) {
        if ($_POST['pass'] == $row[`password`] && $_POST['username'] == $row[`username`]) {
            if (!session_id())
                session_start();
            $_SESSION['logon'] = true;

            header('Location: disucssion.php');
            die();
        }
    }
}

?>

<form action="/login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="pass">Password:</label>
    <input type="text" id="pass" name="pass"><br><br>
    <input type="submit" value="Submit">
</form>