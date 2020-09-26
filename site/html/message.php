<?php
require_once('dbManager.php');
session_start();
// If the user is not logged he will be redirected to the login page
if(!$_SESSION['logon']){
    header('Location: login.php');
}
$dbManager = new dbManager;

// If an id is received it means that a reply is desired by the user
if(isset($_GET['id'])){
    $message = $dbManager->findMessageByID($_GET['id']);
    $user = $dbManager->findUserByID($message['recipient']);
}

// Check that the form is completed
if (isset($_POST['recipient']) && isset($_POST['subject']) && isset($_POST['body'])) {
    $user = $dbManager->findUserByID($_SESSION['id']);
    // Check that the session has a valid id
    if($user != NULL) {
        $recipient = $dbManager->findUserByUsername($_POST['recipient']);
        // Check if the recipient wrote in the form exists in the database. If the user is unknown the site will do nothing
        if($recipient != false){
            $dbManager->addMessage($_POST['subject'], $_POST['body'], $_SESSION['id'], $recipient['id']);
            header('Location: inbox.php');
        }
    }
    else header('Location: login.php');
}
?>

<form action="/message.php" method="post">
    <label for="recipient">Recipient:</label><br>
    <input type="text" id="recipient" name="recipient" value="<?php if($message != NULL){ echo $user['username'];}?>"><br>
    <label for="subject">Subject:</label><br>
    <input type="text" id="subject" name="subject" value="<?php if($message != NULL){echo "RE: ".$message['subject'];}?>"><br>
    <label for="body">Body:</label><br>
    <textarea rows = "5" cols = "60" name="body">Enter details here...</textarea><br>
    <button type="submit">Send</button>
    <button type="submit" formaction="/inbox.php">Cancel</button>
</form>


