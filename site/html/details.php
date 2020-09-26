<?php
require_once('dbManager.php');
session_start();
// If the user is not logged he will be redirected to the login page
if(!$_SESSION['logon']){
    header('Location: login.php');
}
$dbManager = new dbManager();

// If the delete button has been clicked we delete the message from the database
if (isset($_GET['btnDelete'])) {
    $dbManager->deleteMessage($_GET['id']);
    // When a messaged is deleted the user is send to the inbox
    header('Location: inbox.php');
} else {
    // Search for the desired message
    $message = $dbManager->findMessageByID($_GET['id']);
    if($message == false){
        header('Location: inbox.php');
    }
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


