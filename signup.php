<?php
//signup.php
include 'connect.php';
include 'head1.php';
?>
<body>

<!-- Navigation -->
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
				
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



<?php 

if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    /*the form hasn't been posted yet, display it
	  note that the action="" will cause the form to post to the same page it is on */
	 echo '<div class="login-form"><form method="post" action="">
	 <h2 class="text-center">Register</h2>       
	 <div class="form-group">
	 <input type="text" class="form-control" placeholder="Name" name="user_name" required="required">
 </div>
 <div class="form-group">
 <input type="email" class="form-control" placeholder="E-mail" name="user_email" required="required">
</div>
<div class="form-group">
            <input type="password" class="form-control" placeholder="Password" name="user_pass" required="required">
		</div>	
		
<div class="form-group">
<input type="password" class="form-control" placeholder="Confirm Password" name="user_pass_check" required="required">
</div>	
		<div class="form-group">

';
?>


<button class="btn btn-primary" onclick="getLocation()" type="button">Locate me</button>


    

<?php
  echo '
  <input class="form-control" type="text" id="lat" placeholder="latitude" name="latitude" readonly>
  <input class="form-control" type="text" id="lon" placeholder="longitude" name="longitude" readonly>
  </div>


		';
		$sql = "select stateid, sname
				from state";
						
		if($state = mysqli_query($con, $sql))
		{
			echo '<div class="form-group">
			<label for="exampleFormControlSelect1">Select your state</label>
			<select  class="form-control" id="exampleFormControlSelect1" name="state">';
			while ($row=mysqli_fetch_row($state)) {
				echo '<option value= ';
				echo $row[0]; 
				echo '>';
				echo $row[1];
				echo '</option>';
			}
			echo '</select></div>';

		}
		
		echo '<div class="form-group">
		<button type="submit" class="btn btn-primary btn-block">Register</button>
	</div>
	  </form><strong><p class="text-center">Registered Users, <a href="signin.php">Sign In </a></p></strong></div>';
	 ?> 
<?php
}
else
{
    /* so, the form has been posted, we'll process the data in three steps:
		1.	Check the data
		2.	Let the user refill the wrong fields (if necessary)
		3.	Save the data 
	*/
	$errors = array(); /* declare the array for later use */
	
	if(isset($_POST['user_name']))
	{
		//the user name exists
		if(!ctype_alnum($_POST['user_name']))
		{
			$errors[] = 'The name can only contain letters and digits.';
		}
		if(strlen($_POST['user_name']) > 30)
		{
			$errors[] = 'The username cannot be longer than 30 characters.';
		}
	}
	else
	{
		$errors[] = 'The username field must not be empty.';
	}
	
	
	if(isset($_POST['user_pass']))
	{
		if($_POST['user_pass'] != $_POST['user_pass_check'])
		{
			$errors[] = 'The two passwords did not match.';
		}
	}
	else
	{
		$errors[] = 'The password field cannot be empty.';
	}
	
	if(!empty($errors)) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
	{
		echo '<div class="container"><strong>Uh-oh.. a couple of fields are not filled in correctly..</strong>';
		echo '<ul class="list-group">';
		foreach($errors as $key => $value) /* walk through the array so all the errors get displayed */
		{
			echo '<li class="list-group-item">' . $value . '</li>'; /* this generates a nice error list */
		}
		echo '</ul></div>';
    /*the form hasn't been posted yet, display it
	  note that the action="" will cause the form to post to the same page it is on */
	 echo '<div class="login-form"><form method="post" action="">
	 <h2 class="text-center">Register</h2>       
	 <div class="form-group">
	 <input type="text" class="form-control" placeholder="Name" name="user_name" required="required">
 </div>
 <div class="form-group">
 <input type="email" class="form-control" placeholder="E-mail" name="user_email" required="required">
</div>
<div class="form-group">
            <input type="password" class="form-control" placeholder="Password" name="user_pass" required="required">
		</div>	
		
<div class="form-group">
<input type="password" class="form-control" placeholder="Confirm Password" name="user_pass_check" required="required">
</div>	
		<div class="form-group">

';
?>


<button class="btn btn-primary" onclick="getLocation()" type="button">Locate me</button>


    

<?php
  echo '
  <input class="form-control" type="text" id="lat" placeholder="latitude" name="latitude" required="required" readonly>
  <input class="form-control" type="text" id="lon" placeholder="longitude" name="longitude" required="required" readonly>
  </div>


		';
		$sql = "select stateid, sname
				from state";
						
		if($state = mysqli_query($con, $sql))
		{
			echo '<div class="form-group">
			<label for="exampleFormControlSelect1">Select your state</label>
			<select  class="form-control" id="exampleFormControlSelect1" name="state">';
			while ($row=mysqli_fetch_row($state)) {
				echo '<option value= ';
				echo $row[0]; 
				echo '>';
				echo $row[1];
				echo '</option>';
			}
			echo '</select></div>';

		}
		
		echo '<div class="form-group">
		<button type="submit" class="btn btn-primary btn-block">Register</button>
	</div>
	  </form><strong><p class="text-center">Registered Users, <a href="signin.php">Sign In </a></p></strong></div>';
	  
	}
	else
	{
		//the form has been posted without, so save it
		//notice the use of , keep everything safe!
		//also notice the sha1 function which hashes the password
		$nam = $_POST['user_name'];
		$nam = mysqli_real_escape_string($con,$nam);
		$em = $_POST['user_email'];
		$em = mysqli_real_escape_string($con,$em);
		$st = $_POST['state'];
		$st = mysqli_real_escape_string($con,$st);
		$pass = sha1($_POST['user_pass']);
		$pass = mysqli_real_escape_string($con,$pass);
		$lat = $_POST['latitude'];
		$lat = mysqli_real_escape_string($con,$lat);
		$lon = $_POST['longitude'];
    	$lon = mysqli_real_escape_string($con,$lon);
		$sql = "INSERT INTO users(name, email, password, state_id)
				VALUES('" .($nam) . "',
					   '" . ($em). "',
					   '" . ($pass) . "',
					   '" . ($st) . "'
						)";
						
		$result = mysqli_query($con, $sql);
		$last_id = mysqli_insert_id($con);
		$sql = "insert into location (uid,  curr_latitude, curr_longitude) values
				   ( '" .($last_id) . "',
				   '" .($lat) . "',
				   '" .($lon) . "')";
   		$sta = mysqli_query($con, $sql);
		if(!$result && !$sta)
		{
			//something went wrong, display the error
			echo 'Something went wrong while registering. Please try again later.';
			echo mysqli_error($con); //debugging purposes, uncomment when needed
		}
		else
		{
			$url = 'index.php';
			echo '<script language="javascript">window.location.href ="'.$url.'"</script>';
		
			
			}
	}
}
//	include('footer.php');

