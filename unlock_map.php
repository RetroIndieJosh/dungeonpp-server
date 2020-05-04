<?php

require( "dppdb.php" );

$db = db_connect();

$map_id = db_get( "id" );
if( $map_id == null ) {
        $x = db_require_get( "x" );
        $y = db_require_get( "y" );
        $sql = "SELECT * FROM MapEnemies WHERE x={$x} AND y=${y}";
} else {
        $sql = "SELECT * FROM MapEnemies WHERE map_id={$map_id}";
}
echo $sql;
$result = $db->query( $sql );
if( $result->num_rows == 0 ) {
        if( $map_id == null ) {
                $sql = "SELECT id FROM Maps WHERE x={$x} AND y=${y}";
                $result = $db->query( $sql );
                $map_id = $result->fetch_assoc()["id"];
        }

        $sql = "DELETE FROM MapTiles WHERE map_id={$map_id}";
        $result = $db->query( $sql );
        $sql = "DELETE FROM Maps WHERE id={$map_id}";
        $result = $db->query( $sql );
        echo "{$sql}<br>Map deleted (empty)<br>";
        exit;
}
$result->free();

echo "Keep map ({$result->num_rows} enemies)";
if( $map_id == null ) {
        $sql = "UPDATE Maps SET is_locked=false WHERE x={$x} AND y={$y}";
} else {
        $sql = "UPDATE Maps SET is_locked=false WHERE id={$map_id}";
}

$result = $db->query( $sql );
if( $result == false ) {
        echo "ERR Failed to unlock map";
        exit;
}

db_disconnect( $db );
