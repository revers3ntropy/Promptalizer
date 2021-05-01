<?php
// shows errors
//ini_set('display_errors', true);
//error_reporting(E_ALL);

$username = "";
$password = "";
$db_name = "";

// list of connections for each database
$connection = mysqli_connect('p:localhost', $username, $password, $db_name)
or die('connection failed');

function query ($query) {

    global $connection;

    return mysqli_query($connection, $query);
}