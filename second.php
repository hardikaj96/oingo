
<!-- Navigation -->


<html>
    <head>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="css/modern-business.css" rel="stylesheet">
<style type="text/css">
  html { height: 100% }
  body { height: 100%; margin: 0; padding: 0 }
  #map_canvas { height: 100% }
</style>
<script type="text/javascript"
  src=
"http://maps.googleapis.com/maps/api/js?key=AIzaSyB7RI0dAxn5-0vPv4Log-Nj6eiFF5HAdZU&sensor=false">
</script>


<script type="text/javascript" src="map.js"></script>
<script type="text/javascript">
  function initialize(ro) {
    var locations= new Array();
    var i=0;
    for (i=0;i<ro.length;i++){
      locations[i] = new Array();
      for (j=0; j<ro[i].length;j++){
        locations[i][j] = ro[i][j];
      }
    }
  
  
    var myOptions = {
      center: new google.maps.LatLng(40.717239,-73.9902456),
      zoom: 8,
      mapTypeId: google.maps.MapTypeId.ROADMAP

    };
    var map = new google.maps.Map(document.getElementById("default"),
        myOptions);

    setMarkers(map,locations)

  }



  function setMarkers(map,locations){

      var marker, i

for (i = 0; i < locations.length; i++)
 {

 var tit = locations[i][0]
 var lat = locations[i][2]
 var long = locations[i][3]
 var con =  locations[i][1]

 latlngset = new google.maps.LatLng(lat, long);

  var marker = new google.maps.Marker({
          map: map, title: tit , position: latlngset
        });
        map.setCenter(marker.getPosition())


        var content = '<H4><b>'+ tit +  '</b></H4>' + "<h6> " + con + "</h6>"

  var infowindow = new google.maps.InfoWindow()

google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){
        return function() {
           infowindow.setContent(content);
           infowindow.open(map,marker);
        };
    })(marker,content,infowindow));

  }
  }



  </script>
 </head>
 <?php 
include('connect.php');
if($_SERVER['REQUEST_METHOD'] != 'POST'){
  echo "no access";
}
else
{
  $dt = $_POST['date'];
  echo $dt;
  $ti = $_POST['time'];
  echo $ti;
  $ts = $dt.' '.$ti;
  $lat = $_POST['latitude'];
  echo $lat;
  $lon = $_POST['longitude'];
  echo $lon;
  $sql = "select distinct n1.title, n1.content, n1.latitude, n1.longitude from
  (select f.uid, f.vstatus, f.tagid, s.stateid, f.fid 
 from users u, state s, filter f, schedule sc
  where  u.state_id = s.stateid AND
  u.id = '".($_SESSION['user_id'])."'
  and s.stateid = f.stateid AND
 f.uid = u.id AND
  f.scheduleid = sc.sid 
  order by f.uid AND
  check_schedule('".($ts)."', sc.type, sc.stdate, sc.enddate, sc.vstart, sc.vend) = 1 AND
  getdistance('".($lat)."','".($lon)."', f.latitude, f.longitude) <= f.radius) as f1,
  (select DISTINCT n.nid, n.content, n.visible_to, nt.tagid, n.uid, n.title, u1.name, n.latitude, n.longitude
 from note n, tagging nt, schedule sc, users u, users u1
  where n.nid = nt.nid AND
  n.uid = u1.id AND

   u.id = '".($_SESSION['user_id'])."' AND
   n.scheduleid = sc.sid AND
  check_schedule('".($ts)."', sc.type,  sc.vstart, sc.vend,sc.stdate, sc.enddate) = 1 AND
 getdistance('".($lat)."','".($lon)."', n.latitude, n.longitude) <= n.radius_of_interest) as n1
 
 where ((f1.tagid IS NOT NULL AND n1.tagid IS NOT NULL AND f1.tagid = n1.tagid) OR (f1.tagid IS NULL)) AND check_if_visible(f1.vstatus, n1.visible_to,f1.uid, n1.uid) = 1";

$sql = 'select title, content, latitude, longitude from note';
$note = mysqli_query($con, $sql);
if(mysqli_num_rows($note)!=0)
{
  $row=mysqli_fetch_all($note,MYSQLI_NUM);
  $ro = json_encode($row);  
  echo $ro;

}
}

?>
 <body onload='initialize(<?php echo $ro; ?>)'>
 <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
				
				 <div class="container">
						<a class="navbar-brand" href="index.php">Oingo</a>
						<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>
						<div class="collapse navbar-collapse" id="navbarResponsive">
							<ul class="navbar-nav ml-auto">
              <li class="nav-item">
					<a class="nav-link" href="second.php">Demo</a>
				</li>
					<?php	if(isset($_SESSION['signed_in'])){ ?>
							<li class="nav-item">
					<a class="nav-link" href="note.php">PostNotes</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="filter.php">Filter</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="friend.php">Friends</a>
        </li>
        <?php
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
          </div><br><br><br>
<div class="container">
  <div class="col-lg-4 mb-4">
  <h1 class="my-3">Location</h1>
  <form action="user.php" method="post">
<div class="form-group">
  <label for="starttime">Select Time</label>
  <input type="time" class="form-control" id="starttime" name="starttime" value="00:00:59"  required="require">
</div>
<div class="form-group">
  <label for="date">Select Date</label>
  <input type="date" class="form-control" id="date" name="date" required="require">
</div>
<div class="form-group">

<button onclick="getloc()" type="button">Locate me</button>


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
}
</script>
        <!--map div-->
        <div id="map"></div>
    


  <input class="form-control" type="text" id="lat" placeholder="latitude" name="latitude" readonly>
  <input class="form-control" type="text" id="lon" placeholder="longitude" name="longitude" readonly>
  </div>

</div><div class="form-group"> 
<input class="btn btn-primary" type="submit" value="Check notes" name="">

</div>
          </form>
          <div id="default" style="width:100%; height:100%"></div></div>
 </body>
  </html>

  

