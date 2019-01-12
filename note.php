<?php 
include('connect.php');
include('head.php');

if(!isset($_SESSION['signed_in']))
{
	//the user is not signed in
	echo "<div class='container'> Sorry you have to be sign in to post the note </div>";
}
else if($_SERVER['REQUEST_METHOD'] != 'POST')
{

echo '<br>
<div class="container"><div class="border border-dark rounded"><div class="container">
<form method="POST" action="note.php">
<div class="row">
<div class="col-sm-4 col-md-4 col-lg-6">
<div class="form-group">
<br>
  <label for="title"><strong>Note Title</strong></label>
  <input type="text" class="form-control" id="title" placeholder="Enter Title" name="title" required="required">
</div>
<div class="form-group"><strong>Description</strong>
    <textarea class="form-control" id="desc" rows="2" placeholder="Enter Description" name="desc" required="required"></textarea>
  </div>
  <div class="form-group"> 
<label><strong>Comments</label><br>
<div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="comment" id="yes" value="1" checked>
<label class="form-check-label" for="yes">
  Enable
</label>
</div>
<div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="comment" id="no" value="0">
<label class="form-check-label" for="no">
  Disable
</label>
</div>
</div>


  <div class="form-group">
    <label for="visibility">Note visible to </label>
    <select class="form-control" id="visibility" name="visible">
      <option value="self">Self</option>
      <option value="friend">Friend</option>
      <option value="public" selected>Public</option>
    </select>
  </div>


  <div class="form-group">
    <label for="repeat">Select Schedule for note visibility</label>
    <select class="form-control" id="repeat" name="repeat">
      
      <option value="daily" selected>Daily</option>
      <option value="weekly">Weekly</option>
      <option value="monthly">Monthly</option>
    </select>
  </div><div class="form-row">
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
</div>

<div class="form-group">
<div class="row"><div class="col-sm-4 col-lg-3">
<label for="tags">Select Tags</label></diV><div class="col-sm-4 col-lg-8">

<select multiple class="form-control selectpicker" data-live-search="true" id="tags" name="tags[]">';
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
    echo '</select></div></div>
    </div>';
  }
echo '</div>
<div class="col-sm-4 col-md-4 col-lg-6">  
<br>
';
echo '  



<div class="form-group">

';
?>




    

<?php
  echo '<div class="form-row"><div class="col">
  <button onclick="getloc()" type="button" class="btn btn-primary"><strong>Locate me</strong></button>
  </div>
  <div class="col">
  <input class="form-control" type="text" id="lat" placeholder="latitude" name="latitude" readonly>
  </div><div class="col">
  <input class="form-control" type="text" id="lon" placeholder="longitude" name="longitude" readonly>
  </div></div></div>
  
  <!--map div--><div class="w-100">
  <div class="w-100"  id="map"></div></div>
  <div class="form-group">
  <label for="radius">Radius of Interest</strong></label>
  <input type="text" class="form-control" id="radius" name="radius" placeholder="Enter Radius" required="required" readonly >
</div>

  </div></div>
</div><div class="form-group"> <div class="container">
<input class="btn btn-primary" type="submit" value="Create Note">
</div>
</div></form></div>
</div></div><br>

';

  }
else{
    $tit = $_POST['title'];
	  $tit = mysqli_real_escape_string($con,$tit);
	  $desc = $_POST['desc'];
		$desc = mysqli_real_escape_string($con,$desc);
		$com = $_POST['comment'];
		$com = mysqli_real_escape_string($con,$com);
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

    $sql = "insert into schedule (stdate, enddate, vstart, vend, type) values
    ('" .($sd) . "',
    '" . ($ed). "',
    '" . ($st) . "',
    '" . ($et) . "',
    '" . ($rep) . "'
   )";
   $schedule = mysqli_query($con, $sql);
   
   $last_id = mysqli_insert_id($con);
    
    $sql= "insert into note (uid, title, content, latitude, longitude, radius_of_interest, visible_to, able_to_comment, scheduleid)
     values
    ('" .($_SESSION['user_id']) . "',
    '" . ($tit). "',
    '" . ($desc). "',
    '" . ($lat) . "',
    '" . ($lon) . "',
    '" . ($rad) . "',
    '" . ($vis) . "',
    '" . ($com) . "',
    '" . ($last_id) . "'
   )";
   $note = mysqli_query($con, $sql);
   $last_id = mysqli_insert_id($con);
   $tags_arr = $_POST['tags'];
      
     for ($i=0; $i < sizeof($tags_arr); $i++) {
   $sql = "insert into tagging (nid, tagid) values
    ('" .($last_id) . "',
    '" . ($tags_arr[$i]). "'
   )";
   $notetag = mysqli_query($con, $sql);
     }
		if(!$note)
		{
			//something went wrong, display the error
			echo 'Something went wrong while registering. Please try again later.';
			echo mysqli_error($con); //debugging purposes, uncomment when needed
    }
    else{
      $url = 'index.php';
      echo '<script language="javascript">window.location.href ="'.$url.'"</script>';
    }
}
