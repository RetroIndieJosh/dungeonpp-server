<?php

require( "dppdb.php" );

$map_id = db_require_get( "map_id" );
$power_level = db_require_get( "power_level" );
$crystals = db_require_get( "crystals" );
$x = db_require_get( "x" );
$y = db_require_get( "y" );
$owner_id = db_require_get( "owner_id" );

$db = db_connect();

// find enemy ID from server
$sql = "SELECT id FROM Enemies WHERE id=(SELECT max(id) FROM Enemies)";
$result = $db->query( $sql );
$enemy_id = 1 + $result->fetch_assoc()["id"];
$result->free();

// add enemy to database
$sql = "INSERT INTO Enemies (power_level, crystals, x, y, owner_id) VALUES "
       . "({$power_level}, {$crystals}, {$x}, {$y}, {$owner_id})";
$result = $db->query( $sql );
if( $result == false ) {
        echo "ERR Failed to create Enemy {$enemy_id}";
        exit;
}

// link enemy to map
$sql = "INSERT INTO MapEnemies (map_id, enemy_id) VALUES ({$map_id}, {$enemy_id})";
$result = $db->query( $sql );
if( $result == false ) {
        echo "ERR Failed to add Enemy {$enemy_id} to Map {$map_id}";
        exit;
}

db_disconnect($db);

echo $enemy_id;

?>
