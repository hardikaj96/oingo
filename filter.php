<?php 
include('connect.php');
include('head.php');

if(!isset($_SESSION['signed_in']))
{
	//the user is not signed in
	echo "sorry you have to be sign in to post to that topic";
}
else if($_SERVER['REQUEST_METHOD'] != 'POST')
{

echo '<br>
<div class="container"><div class="border border-dark rounded"><div class="container">
<form method="POST" action="filter.php">


<div class="row">
<div class="col-sm-4 col-md-4 col-lg-6">
  <div class="form-group"><strong><br>
  <label for="tags">Select Tags</label>
  <select class="form-control" id="tags" name="tags">';
  $sql = "select tagid, tagname
				from tags";
						
		if($tags = mysqli_query($con, $sql))
		{
			while ($row=mysqli_fetch_row($tags)) {
				echo '<option value= ';
				echo $row[0]; 
				echo '>';
				echo $row[1];
				echo '</option>';
			}
			echo '</select></div>';

    }
   echo ' <div class="form-group">
  <label for="state">Select State</label>
  <select class="form-control" id="state" name="state">';
  $sql = "select stateid, sname
				from state";
						
		if($state = mysqli_query($con, $sql))
		{
			while ($row=mysqli_fetch_row($state)) {
				echo '<option value= ';
				echo $row[0]; 
				echo '>';
				echo $row[1];
				echo '</option>';
			}
			echo '</select></div>';

		}
echo '
  

  <div class="form-group">
    <label for="visibility">Filter active for </label>
    <select class="form-control" id="visibility" name="visible">
      <option value="self">Self</option>
      <option value="friend">Friend</option>
      <option value="public" selected>Public</option>
    </select>
  </div>


  
  <div class="form-group">
    <label for="repeat">Select Schedule for Filters</label>
    <select class="form-control" id="repeat" name="repeat">
      <option value="daily" selected>Daily</option>
      <option value="weekly">Weekly</option>
      <option value="monthly">Monthly</option>
    </select>
  </div>
  <div class="form-row">
  <div class="col">
  <div class="form-group">
  <label for="startdate">Enter Start date</label>
  <input type="date" class="form-control" id="startdate" name="startdate" required="required">
</div>
</div>
<div class="col">
<div class="form-group">
  <label for="enddate">Enter End date</label>
  <input type="date" class="form-control" id="enddate" name="enddate" required="required">
</div>
</div>
</div>
<div class="row">
<div class="col">
<div class="form-group">
  <label for="starttime">Enter Start Time</label>
  <input type="time" class="form-control" id="starttime" name="starttime" value="11:59:59" required="required">
</div>
</div>
<div class="col">
<div class="form-group">
  <label for="endtime">Enter End Time</label>
  <input type="time" class="form-control" id="endtime" name="endtime" value="23:59:59" required="required">
</div>
</div>
</div> </strong>
<div class="form-group">

';
?>


<script>

function getloc() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    alert("Geolocation is not supported by this browser.");
  }
}

function showPosition(position) {
  document.getElementById('lat').value = position.coords.latitude;
  document.getElementById('lon').value = position.coords.longitude;
  document.getElementById('radius').readOnly = false;
}

</script>

<?php
  echo '
  <div class="form-row"><div class="col">
  <button onclick="getloc()" type="button" class="btn btn-secondary"><strong>Locate me</strong></button>
  </div>
  <div class="col">
  <input class="form-control" type="text" id="lat" placeholder="latitude" name="latitude" readonly>
  </div><div class="col">
  <input class="form-control" type="text" id="lon" placeholder="longitude" name="longitude" readonly>
  </div></div></div>
  </div>
<div class="col-sm-4 col-md-4 col-lg-6">
  <br><!--map div--><div class="w-100">
  <div class="w-100"  id="map"></div></div>
  <div class="form-group">
  <label for="radius"><strong>Radius of Interest</strong></label>
  <input type="text" class="form-control" id="radius" name="radius" placeholder="Enter Radius" required="required" readonly >
</div>
  </div>
  <div class="container">
<div class="form-group"> 
<input class="btn btn-primary" type="submit" value="Create Filter">
</div>
</div></form></div><br></div><br>
';

  }
else{
		$vis = $_POST['visible'];
		$vis = mysqli_real_escape_string($con,$vis);
		$rad = $_POST['radius'];
		$rad = mysqli_real_escape_string($con,$rad);
		$sd = $_POST['startdate'];
		$sd = mysqli_real_escape_string($con,$sd);
		$st = $_POST['starttime'];
		$st = mysqli_real_escape_string($con,$st);
		$ed = $_POST['enddate'];
		$ed = mysqli_real_escape_string($con,$ed);
		$et = $_POST['endtime'];
		$et = mysqli_real_escape_string($con,$et);
		$lat = $_POST['latitude'];
		$lat = mysqli_real_escape_string($con,$lat);
		$lon = $_POST['longitude'];
    $lon = mysqli_real_escape_string($con,$lon);
    $rep = $_POST['repeat'];
    $rep = mysqli_real_escape_string($con,$rep);
    $tag = $_POST['tags'];
    $tag = mysqli_real_escape_string($con,$tag);
    $sta = $_POST['state'];
    $sta = mysqli_real_escape_string($con,$sta);
    

    $sql = "insert into schedule (stdate, enddate, vstart, vend, type) values
    ('" .($sd) . "',
    '" . ($ed). "',
    '" . ($st) . "',
    '" . ($et) . "',
    '" . ($rep) . "'
   )";
   $schedule = mysqli_query($con, $sql);
   
   $last_id = mysqli_insert_id($con);
    
    $sql= "insert into filter (uid, vstatus, tagid, stateid, latitude, longitude, radius,  scheduleid)
     values
    ('" .($_SESSION['user_id']) . "',
    '" . ($vis). "',
    '" . ($tag). "',
    '" . ($sta) . "',
    '" . ($lat) . "',
    '" . ($lon) . "',
    '" . ($rad) . "',
    '" . ($last_id) . "'
   )";
   $filter = mysqli_query($con, $sql);
		if(!$filter)
		{
			//something went wrong, display the error
			echo 'Something went wrong while registering. Please try again later.';
			echo mysqli_error($con); //debugging purposes, uncomment when needed
    }
    else{
      $sql = "select *
        from filter
        where uid = '".($_SESSION['user_id'])."'";
						
		if($filter = mysqli_query($con, $sql))
		{
      $url = 'index.php';
      echo '<script language="javascript">window.location.href ="'.$url.'"</script>';
    }


		}
    }
