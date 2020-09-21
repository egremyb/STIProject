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

if (isset($_POST['recipient']) && isset($_POST['subject']) && isset($_POST['body'])) {
    $stmt = $file_db->prepare("SELECT * FROM Users WHERE id=:id");
    $stmt->execute(['id' => $_SESSION['id']]);
    $user = $stmt->fetch();
    if($user != NULL) {
        $users = $file_db->query('SELECT * FROM Users');
        // Check if the user sent by the form exists in the database
        foreach ($users as $row) {
            if ($_POST['recipient'] == $row['username']) {
                $date = new DateTime();
                $date = $date->format('d-m-Y H:i:s');
                $data = [
                    'subject' => $_POST['subject'],
                    'body' => $_POST['$body'],
                    'sender' => $_SESSION['id'],
                    'recipient' => $row['id'],
                    'date' => $date
                ];
                var_dump($row['id']);
                $sql = "INSERT INTO Messages (subject, body, sender, recipient, date) VALUES (:subject, :body, :sender, :recipient, :date)";
                $stmt2 = $file_db->prepare($sql);
                $stmt2->bindParam(':subject',$_POST['subject']);
                $stmt2->bindParam(':body',$_POST['body']);
                $stmt2->bindParam(':sender',$_SESSION['id']);
                $stmt2->bindParam(':recipient',$row['id']);
                $stmt2->bindParam(':date',$date);
                $stmt2->execute();
                //;
            } else {
                echo "User unknown please enter a valid username";
            }
        }
    } else {
        header('Location: login.php');
    }
}
?>

<form action="/message.php" method="post">
    <label for="recipient">Recipient:</label><br>
    <input type="text" id="recipient" name="recipient"><br>
    <label for="subject">Subject:</label><br>
    <input type="text" id="subject" name="subject"><br>
    <label for="body">Body:</label><br>
    <textarea rows = "5" cols = "60" name="body">Enter details here...</textarea><br>
    <button type="submit">Send</button>
    <button type="submit" formaction="/inbox.php">Cancel</button>
</form>


