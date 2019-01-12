<?php 
include('connect.php');
include('head.php');

if(!isset($_SESSION['signed_in']))
{
	//the user is not signed in
	echo "<div class='container'> Sorry you have to be sign in to post the comment on the note </div>";
}
else if(($_SERVER['REQUEST_METHOD'] != 'GET')&&($_SERVER['REQUEST_METHOD'] != 'POST'))
{

echo '<br><br><div class="container"><h4>no notes available to comment<h4></div><br><br>';
}
else{
  
  if(isset($_POST['comment']) && isset($_POST['nid'])&& isset($_POST['userid'])){
    $cont = $_POST['comment'];
    $cont = mysqli_real_escape_string($con,$cont);
    $nid = $_POST['nid'];
    $nid = mysqli_real_escape_string($con,$nid);
    $uid = $_POST['userid'];
    $uid = mysqli_real_escape_string($con,$uid);
    $sql ="insert into comments (nid, id, commentcontent) values 
    ('".$nid."','".$uid."','".$cont."')"; 
    
    $comment = mysqli_query($con, $sql);
  }
  if(isset($_GET['nid']) || isset($_POST['nid'])){
    if(isset($_GET['nid'])){
      $nid = $_GET['nid'];
    }
    if(isset($_POST['nid'])){
      $nid = $_POST['nid'];
      
    }
    $nid = mysqli_real_escape_string($con,$nid);
    

    $sql = "select title, content, u.name
    from note n, users u
    where n.uid = u.id and nid = '".($nid). "'";
    $note = mysqli_query($con,$sql);

    $row = mysqli_fetch_row($note);
    $title = $row[0];
    $content = $row[1];
    $creator = $row[2];    
    echo '<br><br><div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto">
      <ul class="list-group"><li class="list-group-item">
        <h2>';
        echo $title;
        echo '</h2>
        <p class="lead">'.$content.'</p>
        <div class="row">
        
      <div class="col-lg-2 col-md-2">
      <Strong>Comments:</strong>
      </div>';
        
    $sql = "select c.commentcontent, c.nid, u1.name, u.name
    from comments c, users u, note n, users u1
    where c.id = u.id and n.nid = c.nid and n.uid = u1.id
    and c.nid = '".($nid). "'";
    $com = mysqli_query($con,$sql);
    
    if(mysqli_num_rows($com)!=0){
      echo '
      <div class="col-lg-6 col-md-6"><ul class="list-group">';
        while($row=mysqli_fetch_row($com)){
          echo '<li class="list-group-item"> <strong>';
          echo $row[0];
          echo '</strong><span class="text-muted" style="float:right;">&nbsp;&nbsp;by&nbsp;';
          echo $row[3];
          echo '</span></li>';
        }
        echo '</ul></div></div><br><div class="row"><div class="col-lg-2 col-md-2"><strong>   </strong></div><div class="col-lg-6 col-md-6">
        <form class="form-inline" action="comment.php" method="POST">
      <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="inlineFormInput" placeholder="Write Comment" name="comment">
      <input type="hidden" name="nid" value="'.$nid.'">
      <input type="hidden" name="userid" value="'.($_SESSION['user_id']).'">
      <button type="submit" class="btn btn-primary">Send</button>
    </form></div>';
      }
    else {
      echo '
      <form class="form-inline" action="comment.php" method="POST">
      <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="inlineFormInput" placeholder="Write Comment" name="comment">
      <input type="hidden" name="nid" value="'.$nid.'">
      <input type="hidden" name="userid" value="'.($_SESSION['user_id']).'">
      <button type="submit" class="btn btn-primary">Comment</button>
    </form>';
    }
    echo '</li></ul></div>';
  }
  }
