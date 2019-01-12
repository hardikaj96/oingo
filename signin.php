-<?php
//signin.php
include 'connect.php';
include 'head.php';



//first, check if the user is already signed in. If that is the case, there is no need to display this page
if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
	echo '<div class="container"> You are already signed in, you can <a href="signout.php">sign out</a> if you want. </strong></div><br>
<div class="container"><a href="index.php"><div class="jumbotron"><STRONG><h1 class="text-center">View Notes</h1></strong></div></div>';
}
else
{
	if($_SERVER['REQUEST_METHOD'] != 'POST')
	{
		
		 echo '<div class="login-form">
		 <form action="" method="post">
			 <h2 class="text-center">Log in</h2>       
			 <div class="form-group">
				 <input type="email" class="form-control" placeholder="Email" name="email" required="required">
			 </div>
			 <div class="form-group">
				 <input type="password" class="form-control" placeholder="Password" name="pass" required="required">
			 </div>
			 <div class="form-group">
				 <button type="submit" class="btn btn-primary btn-block">Log in</button>
			 </div>      
		 </form>
		 <p class="text-center"><a href="signup.php">Create an Account</a></p>
	 </div>
	 ';
	}
	else
	{
		/* so, the form has been posted, we'll process the data in three steps:
			1.	Check the data
			2.	Let the user refill the wrong fields (if necessary)
			3.	Varify if the data is correct and return the correct response
		*/
		$errors = array(); /* declare the array for later use */
		
		if(!isset($_POST['email']))
		{
			$errors[] = 'The username field must not be empty.';
		}
		
		if(!isset($_POST['pass']))
		{
			$errors[] = 'The password field must not be empty.';
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
			echo '<div class="login-form">
			<form action="" method="post">
				<h2 class="text-center">Log in</h2>       
				<div class="form-group">
					<input type="email" class="form-control" placeholder="Email" name="email" required="required">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" placeholder="Password" name="pass" required="required">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block">Log in</button>
				</div>      
			</form>
			<p class="text-center"><a href="signup.php">Create an Account</a></p>
		</div>
		';
		}
		else
		{
			//the form has been posted without errors, so save it
			//notice the use of , keep everything safe!
			//also notice the sha1 function which hashes the password4
			
			$em = $_POST['email'];
			$em = mysqli_real_escape_string($con,$em);
			$pass = $_POST['pass'];
			$pass = mysqli_real_escape_string($con,$pass);
			$sql = "SELECT 
						id,
						name,
						state_id
					FROM
						users
					WHERE
						email = '" . ($em) . "'
					AND
						password = '" . sha1($pass) . "'";
						
			$result = mysqli_query($con,$sql) or trigger_error("Sorry there is an account assigned to that userid");

 

 if (mysqli_affected_rows($con) == 0){
	 echo'<br><br><div class="container"><strong>Incorrect crredentials, please try again.</strong></div>';
 }
else if (mysqli_affected_rows($con) == 1) { // Available.



$row = mysqli_fetch_array ($result, MYSQLI_NUM);
$body = "Thank you for login. <br />";
$_SESSION['signed_in'] = true;
					
						$_SESSION['user_id'] 	= $row[0];
						$_SESSION['user_name'] 	= $row[1];
						$_SESSION['user_level'] = $row[2];


						
					//session_regenerate_id();
					mysqli_close($con); // Close the database connection.

					$url = 'index.php';
					echo '<script language="javascript">window.location.href ="'.$url.'"</script>';
exit();

}else {
session_start();
	  $_SESSION["signed_in"] = false;
}

			
		}
	}
}

?>