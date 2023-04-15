<?php

include_once "config.php";

$connection = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

if ( !$connection ) {
    throw new Exception( "Cannot connect to database" );
} else {
    echo "Connected to database<br/>";

    /*
    //making an query to insert into the table
     *add: INSERT INTO tasks(task, date) VALUES ('Do something', '2023-04-15')
     *view: SELECT * FROM tasks
     *delete: DELETE FROM tasks
     *to start id from 1 again: TRUNCATE tasks
     */
    $result = mysqli_query( $connection, "INSERT INTO tasks(task, date) VALUES ('Do something', '2023-04-14')" );

//We can use mysqli_fetch_array as well, it will give an array as result

    while ( $data = mysqli_fetch_assoc( $result ) ) {

        echo "<pre>";

        print_r( $data );

        echo "</pre>";
    }

    //It's ideal to destroy the session of connection manually after every use of mysqli
    mysqli_close( $connection );
}
