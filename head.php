<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

		<style type="text/css">
	.login-form {
		width: 340px;
    	margin: 50px auto;
	}
    .login-form form {
    	margin-bottom: 15px;
        background: #f7f7f7;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        padding: 30px;
    }
    .login-form h2 {
        margin: 0 0 15px;
    }
    .form-control, .btn {
        min-height: 38px;
        border-radius: 2px;
    }
    .btn {        
        font-size: 15px;
        font-weight: bold;
    }
</style>
<style type="text/css">
          #map{ width:700px; height: 500px; }
		</style>
		<style type="text/css">
  html { height: 100% }
  body { height: 100%; margin: 0; padding: 0 }
  #map_canvas { height: 100% }
</style>
	<title>OINGO</title>
	
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB7RI0dAxn5-0vPv4Log-Nj6eiFF5HAdZU"></script>

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
 
<!-- Bootstrap core CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="css/modern-business.css" rel="stylesheet">
<script>

function getLocation() {
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
</head>

<body>

				
				<?php
						
						echo ' <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
						<div class="container">
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
				echo   '<li class="nav-item"><a class="nav-link" href="signout.php">Sign Out</a></li> ';
				
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




