<?php
$con=mysqli_connect ("localhost","root","","oingoo");
if (!$con) {
echo "failed to connect mysql: ". mysqli_connect_error();
}
session_start();
?>
