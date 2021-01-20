<?php
require_once('../class/dbManager.php');
require_once('../class/identityManagement.php');
try {
    // Connection to the database
    $dbManager = new dbManager();
    session_start();
    IdentityManagement::isSessionValid($_SESSION, $dbManager);

    // If no id is passed to the page an error is sent
    if (!isset($_GET['id'])) {
        $dbManager->closeConnection();
        die('Invalid arguments passed to the page');
    }

    // If the delete button has been clicked we delete the message from the database
    if (isset($_GET['btnDelete']) && isset($_POST['token']) &&
        IdentityManagement::isTokenValid($_SESSION, $_POST['token'])) {
        $dbManager->deleteMessage($_GET['id']);
        $dbManager->closeConnection();
        // When a messaged is deleted the user is send to the inbox
        header('Location: ../inbox.php');
    } else {
        // Search for the desired message to show
        $message = IdentityManagement::isMessageAccessAllowed($_SESSION, $_GET['id'], $dbManager);
    }
} catch(PDOException $e) {
    die('Connection to the database failed');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../css/signin.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

        <meta charset="UTF-8">
        <title>View message</title>
    </head>
    <body>
        <?php require_once('../fragments/navBar.php');?>
        <form action="details.php" method="get">
            <input type="hidden" readonly name="token" value="<?php echo $_SESSION['token'] ?>" />
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-8 col-xl-6">
                        <div class="row">
                            <div class="col text-center">
                                <h1>View Message</h1>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col mt-4">
                                <label for="sender">Sender</label>
                                <input type="text" id="sender" readonly name="sender" class="form-control" placeholder="Sender" value="<?php echo $message['sender']?>">
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col">
                                <label for="recipient">Recipient</label>
                                <input type="text" id="recipient" readonly name="recipient" class="form-control" placeholder="Recipient" value="<?php echo $message['recipient']?>">
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col">
                                <label for="date">Date</label>
                                <input type="text" id="date" readonly name="date" class="form-control" placeholder="Date" value="<?php echo $message['date']?>">
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col">
                                <label for="subject">Subject</label>
                                <input type="text" id="subject" readonly name="subject" class="form-control" placeholder="Subject" value="<?php echo $message['subject']?>">
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col">
                                <label for="subject">Body</label>
                                <textarea rows = "5" cols = "60" readonly class="form-control" placeholder="Enter details here..." name="body" ><?php echo $message['body']?></textarea>
                            </div>
                        </div>

                        <div class="row justify-content-start mt-4">
                            <div class="col">
                                <?php
                                // Depending on the action desired the buttons available will be different
                                if(isset($_GET['deleteForm'])){
                                    echo <<<EOT
                                        
                                        <button type="submit" class="btn btn-primary mt-4" name="btnDelete" value="yes">Delete</button>
                                        <a class="btn btn-secondary mt-4" href="../inbox.php">Cancel</a>
                                        <input type="hidden" name="id" value="{$message['id']}"/>
EOT;
                                }
                                else {
                                    echo <<<EOT
                                        <a class="btn btn-primary mt-4" href="../inbox.php">Go to Inbox</a>
EOT;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>




