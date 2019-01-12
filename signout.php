<?php
include 'connect.php';
unset ($_SESSION['signed_in']);
session_destroy();
//include 'head.php';
//echo "You successfully logged out";
//header ("refresh:1;url=index.php" );
$url = 'index.php';
echo '<script language="javascript">window.location.href ="'.$url.'"</script>';

mysqli_close($con);
exit ();
?>