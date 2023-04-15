<?php
include_once 'config.php';
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$connection) {
    throw new Exception("Connection establishment failed");
} else {
    $action = $_POST['action'] ?? '';

    if (!$action) {
        header('Location: index.php');
        die();
    } else {

        if ('add' == $action) {
            $task = $_POST['task'];
            $date = $_POST['date'];

            if ($task && $date) {
                $query = "INSERT INTO " . DB_TABLE . " (task, date) VALUES ('{$task}', '{$date}')";
                mysqli_query($connection, $query);
                header('Location: index.php?added=true');
            }
        }
    }
}
