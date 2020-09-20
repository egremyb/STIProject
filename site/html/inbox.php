<?php
const ACTIONS = '<span class="actions">
                    <a href="#"><span class="material-icons">reply</span></a>
                    <a href="#"><span class="material-icons">delete</span></a>
                    <a href="#"><span class="material-icons">launch</span></a>
                </span>';

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

    $messages =  $file_db->query('SELECT * FROM messages');
}
catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
}


?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<table class="table messages">
    <thead class="thead-light">
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Sender</th>
            <th scope="col">Subject</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($messages as $msg) {
            echo <<<EOT
                <tr>
                     <th>{$msg['time']}</th>
                     <th>n/a</th>
                     <th>{$msg['title']}</th>
                     <th>
                        <span class="actions">
                            <a href="reply.php?id={$msg['id']}"><span class="material-icons">reply</span></a>
                            <a href="delete.php?id={$msg['id']}"><span class="material-icons">delete</span></a>
                            <a href="details.php?id={$msg['id']}"><span class="material-icons">launch</span></a>
                        </span>
                    </th>
                </tr>
EOT;
        }
    ?>
    </tbody>
</table>

