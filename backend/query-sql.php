<?php
// shows errors
ini_set('display_errors', true);
error_reporting(E_ALL);

$username = "promptal_promptalizer";
$password = "spoonface2005!";
$db_name = "promptal_db";

// list of connections for each database
$connection = mysqli_connect('p:localhost', $username, $password, $db_name)
    or die('connection failed');

mysqli_select_db($connection, "promptal_db");

function query_db ($query) {
    global $connection;
    $dataset = mysqli_query($connection, $query);
    $results = [];
    if ($dataset === true) {
        return true;
    }
    if ($dataset === false) {
	print "Query failed...\n";
	print mysqli_error($connection);
        return false;
    }
    while ($row = mysqli_fetch_row($dataset)) {
	array_push($results, $row);
    }
    return $results;
}

function new_random_id() {
    $query = "SELECT FLOOR (1 + RAND() * 2000000000) AS value FROM prompts HAVING value NOT IN (SELECT DISTINCT id FROM prompts) LIMIT 1";
    $result = query_db($query);
    if ($result === false || count($result) == 0) {
        return false;
    }
    $id = $result[0][0];
    return $id;
}

function store_prompt($args, $prompt) {
    $id = new_random_id();
    if ($id === false) {
        return false;
    }
    $prompt["id"] = $id;
    $query = "INSERT INTO prompts VALUES (".$id.", \"".addslashes(json_encode($args))."\", \"".addslashes(json_encode($prompt))."\");";
    query_db($query);
    return $id;
}

?>
