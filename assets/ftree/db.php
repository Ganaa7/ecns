<?php
$con = mysqli_connect ( "localhost", "root", "", "northwind" );
if (mysqli_connect_errno ()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error ();
}

mysqli_set_charset ( $con, "utf8" );
?>