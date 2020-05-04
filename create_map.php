<?php

require( "dppdb.php" );

$map_width = intval( db_require_get( "width" ) );
$map_height = intval( db_require_get( "height" ) );
$map_x = intval( db_require_get( "x" ) );
$map_y = intval( db_require_get( "y" ) );
$owner_id = intval( db_require_get( "owner_id" ) );

$db = db_connect();

$sql = "INSERT INTO Maps (width, height, x, y, owner_id, is_locked) VALUES ({$map_width}, {$map_height}, {$map_x}, {$map_y}, {$owner_id}, true)"; 
$result = $db->query( $sql );
if( $result == false ) {
        echo "ERR Failed to create map";
        exit;
}

$sql = "SELECT id FROM Maps WHERE id=(SELECT max(id) FROM Maps)";
$result = $db->query( $sql );
$map_id = $result->fetch_assoc()["id"];
$result->free();

for( $y = 0; $y < $map_height; ++$y ) {
        for( $x = 0; $x < $map_width; ++$x ) {
                $pos = $y * $map_width + $x;
                $values = "({$map_id}, {$pos}, 0)";
                $sql = "INSERT INTO MapTiles (map_id, pos, tile_id) VALUES {$values}";
                $result = $db->query( $sql );
                if( $result == false ) {
                        echo "ERR Failed to create tile {$pos}";
                        exit;
                }
        }
}

db_disconnect($db);

echo "{$map_id}";

?>
