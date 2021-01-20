<?php
require_once('../class/dbManager.php');
require_once('../class/identityManagement.php');
require_once('../class/utils.php');
try {
    // Connection to the database
    $dbManager = new dbManager;
    session_start();
    IdentityManagement::isSessionValid($_SESSION, $dbManager);

    // If an id is received it means that a reply is desired by the user
    if (isset($_GET['id'])) {
        $message = IdentityManagement::isMessageAccessAllowed($_SESSION, $_GET['id'], $dbManager);
        // Create a reply content
        $replyContent = "\n\n\n\n" .
                        "---------- Original message ----------\n" .
                        "From: ${message['sender']}\n" .
                        "Sent: ${message['date']}\n" .
                        "To: ${message['recipient']}\n" .
                        "Subject: ${message['subject']}\n" . $message['body'];
    }

    // Check that the form is completed
    if (isset($_POST['recipient']) && isset($_POST['subject']) && isset($_POST['body']) &&
        isset($_POST['token']) && IdentityManagement::isTokenValid($_SESSION, $_POST['token'])){
        // Filter subject and body inputs
        $post_subject = Utils::filterString($_POST['subject']);
        $post_body    = Utils::filterString($_POST['body']);
        // Find user
        $user = $dbManager->findUserByID($_SESSION['id']);
        // Check that the session has a valid id
        if ($user != NULL) {
            $recipient = $dbManager->findUserByUsername($_POST['recipient']);
            // Check if the recipient wrote in the form exists in the database.
            if ($recipient != false) {
                $dbManager->addMessage($post_subject, $post_body, $_SESSION['id'], $recipient['id']);
                $dbManager->closeConnection();
                header('Location: ../inbox.php');
            } else {
                $error = "User unknown please try another user";
            }
        } else {
            $dbManager->closeConnection();
            header('Location: ../login.php');
        }
    }
} catch(PDOException $e) {
    die('Connection to the database failed' . $e);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="../css/common.css">

        <meta charset="UTF-8">
        <title>New message</title>
    </head>
    <body>
        <?php require_once('../fragments/navBar.php');?>
        <form action="message.php" method="post">
            <input type="hidden" readonly name="token" value="<?php echo $_SESSION['token'] ?>" />
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-8 col-xl-6">
                        <div class="row">
                            <div class="col text-center">
                                <h1>New Message</h1>
                                <?php
                                if (isset($error) && !empty($error)) {
                                    echo '<p class="error">' . $error . '</p>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col mt-4">
                                <label for="recipient">Recipient</label>
                                <input type="text" id="recipient" name="recipient" class="form-control" placeholder="Recipient" required value="<?php if($message != NULL){ echo $message['sender'];} else if($_POST['recipient']){ echo $_POST['recipient'];}?>">
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col">
                                <label for="subject">Subject</label>
                                <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject" required value="<?php if($message != NULL){echo "RE: ".$message['subject'];} else if($_POST['recipient']){ echo $_POST['subject'];}?>">
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col">
                                <label for="body">Body</label>
                                <textarea rows = "8" cols = "60"  class="form-control" placeholder="Enter details here..." name="body" required ><?php if(isset($replyContent)){echo $replyContent; } else if($_POST['body']){ echo $_POST['body'];}?></textarea>
                            </div>
                        </div>

                        <div class="row justify-content-start mt-4">
                            <div class="col">
                                <button type="submit" class="btn btn-primary mt-4">Submit</button>
                                <button type="submit" class="btn btn-secondary mt-4" formaction="../inbox.php" formnovalidate>Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>
