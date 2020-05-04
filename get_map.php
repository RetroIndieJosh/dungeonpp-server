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
        echo '{"id": 0, "x": 0, "y": 0}';
        exit;
}

$map = $map_result->fetch_assoc();
$map_id = $map["id"];
$map_width = $map["width"];
$map_height = $map["height"];
$map_x = $map["x"];
$map_y = $map["y"];
$map_result->free();

//echo "{$map_width}x{$map_height}<br />";

//
// GET TILES
//

/*
$sql = "SELECT * FROM MapTiles WHERE map_id={$map_id}";
$result = $db->query( $sql );
if( $result == null || $result->num_rows == 0 ) {
        echo( "ERR Could not retrieve tiles for map #{$map_id}" );
        exit;
}
while( $row = $result->fetch_assoc() ) {
        $pos = $row["pos"];
        $tile_id = $row["tile_id"];
        $x = $pos % $map_width;
        $y = intval( floor( $pos / $map_width ) );
        $tilemap[$x][$y] = $tile_id;
}
$result->free();
 */

//
// GET ENEMIES
//

$sql = "SELECT * FROM MapEnemies WHERE map_id={$map_id}";
$map_enemies_result = $db->query( $sql );
/*
if( $result == false )
        echo( "ERR Could not retrieve enemies for map #{$map_id}" );
        exit;
}
if( $result->num_rows == 0 ) {
        echo( "ERR Map #{$map_id} has no enemies" );
        exit;
}
 */

$enemy_count = $map_enemies_result->num_rows;
if( $enemy_count > 0 ) {
        $enemy_list = '[';
        while( $row = $map_enemies_result->fetch_assoc() ) {
                $enemy_list .= "{";
                $enemy_id = $row["enemy_id"];
                $sql = "SELECT * FROM Enemies WHERE id={$enemy_id}";
                $enemy_result = $db->query( $sql );
                $enemy = $enemy_result->fetch_assoc();
                $enemy_list .= '"powerLevel" : ' . $enemy["power_level"] . ", ";
                $enemy_list .= '"crystals" : ' . $enemy["crystals"] . ", ";
                $enemy_list .= '"x" : ' . $enemy["x"] . ", ";
                $enemy_list .= '"y" : ' . $enemy["y"] . ", ";
                $owner_id = $enemy["owner_id"];
                $enemy_result->free();

                $sql = "SELECT * FROM Players WHERE id={$owner_id}";
                $owner_result = $db->query( $sql );
                $owner = $owner_result->fetch_assoc();
                $enemy_list .= '"ownerId" : "' . $owner["id"] . '",';
                $enemy_list .= '"ownerName" : "' . $owner["name"] . '"';
                $owner_result->free();

                $enemy_list .= "},";
        }
        $enemy_list = substr( $enemy_list, 0, -1 ) . "]";
}

$map_enemies_result->free();
db_disconnect( $db );

$json = "{";
/*
echo '"row" : [';
for( $y = 0; $y < $map_height; ++$y ) {
        echo '{"tile" : [';
        for( $x = 0; $x < $map_width; ++$x ) {
                echo $tilemap[$x][$y];
                if( $x < $map_width - 1 )
                        echo ",";
        }
        echo "]}";
        if( $y < $map_height - 1 )
                echo ",";
}
echo "],";
 */
$json .= "\"id\": {$map_id}, \"x\": {$map_x}, \"y\": {$map_y},";
if( $enemy_count > 0 )
        $json .= "\"enemyList\":{$enemy_list}";
else $json = substr( $json, 0, -1 );
$json .= "}";

echo $json;
