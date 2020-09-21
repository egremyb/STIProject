<?php
session_start();
// If the user is not logged he will be redirected to the login page
if(!$_SESSION['logon']){
    header('Location: login.php');
}
$file_db = new PDO('sqlite:/usr/share/nginx/databases/database.sqlite');
// Set errormode to exceptions
$file_db->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

// If the delete button has been clicked we delete the message from the database
if (isset($_GET['btnDelete'])) {
    $stmt = $file_db->prepare("DELETE FROM Messages WHERE id=:id");
    $stmt->execute(['id' => $_GET['id']]);
    $messageDeleted = $stmt->fetch();
    header('Location: inbox.php');
} else {
    // Search for the desired message
    $stmt = $file_db->prepare("SELECT * FROM Messages WHERE id=:id");
    $stmt->execute(['id' => $_GET['id']]);
    $message = $stmt->fetch();
}
?>

<table>
    <tr>
        <th>Sender :</th>
        <td><?php echo $message['sender']?></td>
    </tr>
    <tr>
        <th>Recipient :</th>
        <td><?php echo $message['recipient']?></td>
    </tr>
    <tr>
        <th>Date :</th>
        <td><?php echo $message['date']?></td>
    </tr>
    <tr>
        <th>Subject :</th>
        <td><?php echo $message['subject']?></td>
    </tr>
    <tr>
        <th>Body :</th>
        <td><?php echo $message['body']?></td>
    </tr>
</table>

<?php
// Depending on the action desired the buttons available will be different
if(isset($_GET['deleteForm'])){
    echo <<<EOT
        <form action="/details.php">
            <button type="submit" formaction="/inbox.php">Cancel</button>
            <input type="hidden" name="id" value="{$message['id']}"/>
            <button type="submit" name="btnDelete" value="yes">Delete</button>
        </form>
EOT;
}
else {
    echo <<<EOT
        <form action="/inbox.php">
            <input type="submit" value="Go to Inbox"/>
        </form>
EOT;
}
?>


