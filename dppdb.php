<?php

const REQUIRE_TOKEN = false;

function db_check_token() {
        if( !REQUIRE_TOKEN ) return true;

        $token = db_require_get( "token" );
        $correct_token = date( 'Y-m-d' ) . "foo";
        if( $token == $correct_token )
                return true;

        echo "ERR Invalid token. Begone hacker!";
        return false;
}

function db_connect() {
        if( db_check_token() == false )
                return null;

        $db_id = intval( db_require_get( "db" ) );
	if( !is_int( $db_id ) ) {
		echo "ERR Version string must be int";
		return null;
	}

        ini_set('display_errors', 1);
        error_reporting( E_ALL );

        $host = "localhost";
        $user = "dppadmin";
        $password = /*redacted*/;
        $dbname = "dungeonpp" . $db_id;

        $link = mysqli_connect( $host, $user, $password, $dbname );

        if( $link ) return $link;

        echo "ERR Connection failed";
        exit;
}


function db_disconnect( $link ) {
        mysqli_close( $link );
}


function db_get( $get_var ) {
        if( isset($_GET[$get_var]) ) 
                return $_GET[$get_var];
        return null;
}


function db_require_get( $get_var ) {
        $val = db_get( $get_var );
        if( $val == null ) {
                echo "ERR Missing GET var '{$get_var}'";
                exit;
        }

        return $val;
}


function db_require_post( $post_var ) {
        if( isset($_POST[$post_var]) ) 
                return $_POST[$post_var];

        echo "ERR Missing POST var '{$post_var}'";
        exit;
}


if( isset($_GET["test"]) ) {
	$db = db_connect();
	if( $db == null ) echo "<p>Failure</p>";
	else echo "<p>Success!</p>";
	db_disconnect($db);
}

?>

