<?php

require( "dppdb.php" );

$db = db_connect();

// find player ID for next player
$sql = "SELECT id FROM Players WHERE id=(SELECT max(id) FROM Players)";
$result = $db->query( $sql );
$player_id = 1 + $result->fetch_assoc()["id"];
$result->free();

// add player to database
$player_name = "Player {$player_id}";
$sql = "INSERT INTO Players (name) VALUES (\"$player_name\")";
$result = $db->query( $sql );
if( $result == false ) {
        echo "ERR Failed to create Player {$player_id}";
        exit;
}

db_disconnect($db);

echo "{\"id\":{$player_id},\"name\":\"{$player_name}\"}";

?>
