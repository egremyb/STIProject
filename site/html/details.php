<?php
require_once('dbManager.php');
session_start();
// If the user is not logged he will be redirected to the login page
if(!$_SESSION['logon']){
    header('Location: login.php');
    exit();
}
$dbManager = new dbManager();

// If the delete button has been clicked we delete the message from the database
if (isset($_GET['btnDelete'])) {
    $dbManager->deleteMessage($_GET['id']);
    // When a messaged is deleted the user is send to the inbox
    //header('Location: inbox.php');
} else {
    // Search for the desired message
    $message = $dbManager->findMessageByID($_GET['id']);
    if($message == false){
        //header('Location: inbox.php');
    }
}
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="css/signin.css">
<form action="details.php" method="get">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-8 col-xl-6">
                <div class="row">
                    <div class="col text-center">
                        <h1>View Message</h1>
                        <p class="text-h3">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia. </p>
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
                                <button type="submit" class="btn btn-secondary mt-4" formaction="/inbox.php">Cancel</button>
                                <input type="hidden" name="id" value="{$message['id']}"/>
EOT;
                        }
                        else {
                            echo <<<EOT
                                <button type="submit" class="btn btn-primary mt-4" formaction="/inbox.php">Go to Inbox</button>
EOT;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>




