<?php 
include('connect.php');
include('head1.php');
echo '
<body onload="getlocation()">

<!-- Navigation -->
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">'; ?>
				
				<?php
						
						echo ' <div class="container">
						<a class="navbar-brand" href="index.php">Oingo</a>
						<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>
						<div class="collapse navbar-collapse" id="navbarResponsive">
							<ul class="navbar-nav ml-auto">
					
					';
						if(isset($_SESSION['signed_in'])){
							echo '<li class="nav-item">
					<a class="nav-link" href="note.php">PostNotes</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="filter.php">Filter</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="friend.php">Friends</a>
				</li>';
				echo '<li class="nav-item"> <a class="nav-link" href="user.php">' . $_SESSION['user_name'] . '</a></li>';
				echo   ' ' . '<li class="nav-item"><a class="nav-link" href="signout.php">Sign Out</a></li> ';
				
							}
						else
						{
							echo '<li  class="nav-item"><a  class="nav-link" href="signin.php">Login</a> </li>';
							echo '<li class="nav-item" ><a  class="nav-link" href="signup.php">Register</a></li>.';
						}
				?>
				
			</ul>
		</div>
	</div>
</nav>


</div>
<?php

if(!isset($_SESSION['signed_in']))
{
	//the user is not signed in
	echo '';
}
else if($_SERVER['REQUEST_METHOD'] != 'POST')
{

echo '

<div class="container">
<div class="col-lg-4 mb-4">
<h1 class="my-3">State</h1>
  <form action="user.php" method="post">
  <div class="form-group">
  <select class="form-control" id="state" name="state">';
  $sql = "select stateid, sname
  from state 
  where stateid not in (select state_id from users where id = '" .($_SESSION['user_id']) . "')";
						
		if($state = mysqli_query($con, $sql))
		{
			while ($row=mysqli_fetch_row($state)) {
				echo '<option value= ';
				echo $row[0]; 
				echo '>';
				echo $row[1];
				echo '</option>';
			}

		}
		
		echo '</select>
		<input class="btn btn-primary" type="submit" value="Update your State" name="st">
		</div></form></div></div>';

  echo '<div class="container">
  <div class="col-lg-4 mb-4">
  <h1 class="my-3">Location</h1>
  <form action="user.php" method="post">
  <div class="form-group">
  <button onclick="getLocation()" type="button">Locate Me</button>
  ';
  $sql = "select curr_latitude, curr_longitude
  from location
  where uid = '" .($_SESSION['user_id']) . "'";

  if($loc = mysqli_query($con, $sql))
		{
			while ($row=mysqli_fetch_row($loc)) {
				echo '<input class="form-control" type="text" id="lat" placeholder="';
				echo $row[0]; 
				echo '" name="latitude" readonly>
        <input class="form-control" type="text" id="lon" placeholder="';
				echo $row[1];
				echo '" name="longitude" readonly>';
			}

    }
    echo '
  </div>
  <div class="w-100">
  <div class="w-100"  id="map"></div></div>
  
  <div class="form-group">
  <label for="starttime">Select Time</label>
  <input type="time" class="form-control" id="starttime" name="time" value="00:00:59"  required="require">
</div>
<div class="form-group">
  <label for="date">Select Date</label>
  <input type="date" class="form-control" id="date" name="date" required="require">
</div>
  <div class="form-group"> 
<input class="btn btn-primary" type="submit" value="Update your Location" name="location">

</div></div>
</form></div></div>
';

  }
else{
	if(isset($_POST['st'])){
		$sta = $_POST['state'];
	$sta = mysqli_real_escape_string($con,$sta);
	
   $sql = "update users
    set state_id = '" . ($sta) . "' 
           where id = '" .($_SESSION['user_id']) . "'";
   $sta = mysqli_query($con, $sql);
   
   if($sta){
    $url = 'user.php';
    echo '<script language="javascript">window.location.href ="'.$url.'"</script>';
   }
	}
	if(isset($_POST['location'])){
		$lat = $_POST['latitude'];
		$lat = mysqli_real_escape_string($con,$lat);
		$lon = $_POST['longitude'];
    $lon = mysqli_real_escape_string($con,$lon);
	$dt = $_POST['date'];
	$dt = mysqli_real_escape_string($con,$dt);
  
  $ti = $_POST['time'];
  $ti = mysqli_real_escape_string($con,$ti);
  
  $ts = $dt.' '.$ti;

    $sql = "update location
    set curr_latitude = '" . ($lat) . "' , curr_longitude = '" . ($lon) . "', current = '".($ts)."'
        where uid = '" .($_SESSION['user_id']) . "'";
   $loc = mysqli_query($con, $sql);
   if($loc){
	
   $url = 'index.php';
   echo '<script language="javascript">window.location.href ="'.$url.'"</script>';
  }
	}
	/*if(isset($_POST['location'])){
		$lat = $_POST['latitude'];
		$lat = mysqli_real_escape_string($con,$lat);
		$lon = $_POST['longitude'];
    $lon = mysqli_real_escape_string($con,$lon);
    

    $sql = "update location
    set curr_latitude = '" . ($lat) . "' , curr_longitude = '" . ($lon) . "'
        where uid = '" .($_SESSION['user_id']) . "'";
   $loc = mysqli_query($con, $sql);
   if($loc){
  
   $url = 'user.php';
   echo '<script language="javascript">window.location.href ="'.$url.'"</script>';
  }
	}*/

	
		
   
}
