<?php 
include('connect.php');
include('head.php');
if(isset($_GET['id']) && isset($_SESSION['signed_in'])){
  $request_to = $_GET['id'];
  $request_to = mysqli_real_escape_string($con,$request_to);
  $sql = "insert into friend (uid1, uid2) values
  ('" .($_SESSION['user_id']) . "',
    '" . ($request_to). "')";
    $req = mysqli_query($con, $sql);
}
if(isset($_GET['accept']) && isset($_SESSION['signed_in'])){
  $accept = $_GET['accept'];
  $accept = mysqli_real_escape_string($con,$accept);
  $sql = "insert into friend (uid1, uid2) values
  ('" .($_SESSION['user_id']) . "',
    '" . ($accept). "')";
    $req = mysqli_query($con, $sql);
}
if(isset($_SESSION['signed_in'])){


echo '<br><div class="container"><div class="border border-dark rounded"><div class="container">

<h1 class="my-3">Friends</h1>
 ';
  $sql = "SELECT id, name from users where id in 
    (select f1.uid2 from friend f1 inner join friend f2 on f1.uid1 = f2.uid2 and f1.uid2 = f2.uid1 and f1.uid1 = '" .($_SESSION['user_id']) ."')";
        
if($friend = mysqli_query($con, $sql))
{
  if(($i=mysqli_num_rows($friend))==0){
    echo 'There are no friends';
  }
  else{
    echo '<div class="row">';
  while ($row=mysqli_fetch_row($friend)) {?>
    <div class="col-lg-4 mb-4">
          <div class="card h-100">
            <h4 class="card-header">
            <!--<a href="user.php?friend= $row[0] "> -->
            <?php
    echo $row[1];
    echo '</a></h4></div></div>';
  }
  echo '</div></div>
  <hr class="hr-danger"><hr class="hr-danger">';
  }
}
echo '<div class="container">
<h1 class="my-1">Pending Requests</h1>
';
$sql = "select id, name from users where id in ( select f.uid1 from friend f where f.uid2 = '".($_SESSION['user_id'])."' 
AND f.uid1 NOT IN( select f1.uid2 from friend f1 inner join friend f2 on f1.uid1 = f2.uid2 and f1.uid2 = f2.uid1 AND f1.uid1='".($_SESSION['user_id'])."'))";
if($friend = mysqli_query($con, $sql))
{
  if(($i=mysqli_num_rows($friend))==0){
    echo 'There are no pending request';
  }
  else{
    echo '<div class="row">';
  while ($row=mysqli_fetch_row($friend)) {?>
    <div class="col-lg-4 mb-4">
          <div class="card h-100">
            <h4 class="card-header">
              
    <?= $row[1] ?>
    <span class="text-muted" style="float:right;"><a href="friend.php?accept=<?= $row[0] ?>">
    <?php
    echo '<small>Accept</small></a></span></h4></div></div>';
  }
  echo '</div></div>  <hr class="hr-danger"><hr class="hr-danger">' ;
  }
}
echo '<div class="container"> 
 <h1 class="my-4">Make New Friends</h1>';
$sql = "SELECT id, name from users where id not in (SELECT id from users where id in (select f1.uid2 from friend f1 inner join friend f2 on f1.uid1 = f2.uid2 and f1.uid2 = f2.uid1 and f1.uid1 = '".($_SESSION['user_id'])."')) 
and id not in (select id from users where id in ( select f.uid2 from friend f where f.uid1 = '".($_SESSION['user_id'])."' AND f.uid2 NOT IN( select f1.uid2 from friend f1 inner join friend f2 on f1.uid1 = f2.uid2 and f1.uid2 = f2.uid1 AND f1.uid1='".($_SESSION['user_id'])."'))) 
and id not in (select id from users where id in ( select f.uid1 from friend f where f.uid2 = '".($_SESSION['user_id'])."'
AND f.uid1 NOT IN( select f1.uid2 from friend f1 inner join friend f2 on f1.uid1 = f2.uid2 and f1.uid2 = f2.uid1 AND f1.uid1=2))) and id <> '".($_SESSION['user_id'])."'";
        
if($friend = mysqli_query($con, $sql))
{
  if(($i=mysqli_num_rows($friend))==0){
    echo 'There are no new users to send the request.';
  }
  else{
    echo '<div class="row">';
  while ($row=mysqli_fetch_row($friend)) {?>
    <div class="col-lg-4 mb-4">
          <div class="card h-100">
            <h4 class="card-header">
            <?= $row[1] ?>
    <span class="text-muted" style="float:right;"><a href="friend.php?id=<?= $row[0] ?>">
    <?php
    echo '<small>Send Request</small></a></span></h4></div></div>';
  }
  echo '</div>';
  }
}
     echo '</div></div>';
}

