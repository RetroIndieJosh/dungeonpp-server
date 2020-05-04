<?php

require( "dppdb.php" );

$db = db_connect();

$map_id = db_get( "id" );
if( $map_id == null ) {
        $x = db_require_get( "x" );
        $y = db_require_get( "y" );

        $sql = "SELECT * FROM Maps WHERE x={$x} AND y={$y}";
} else {
        $sql = "SELECT * FROM Maps WHERE id={$map_id}";
}

$map_result = $db->query( $sql );
if( $map_result->num_rows == 0 ) {
        echo "0";
        exit;
}

$map = $map_result->fetch_assoc();
$is_locked = $map["is_locked"];
$map_result->free();
db_disconnect( $db );

echo $is_locked;
