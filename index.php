<?php 
include('connect.php');
include('head.php');
    ?>
     <?php
    if(!isset($_SESSION['signed_in']))
    {
      //the user is not signed in
      echo '<br><br><div class="container"><h4>Sorry, you have to sign in to get the notes posted by the users of OINGO<h4></div><br><br>
      <div class="container"><div class="jumbotron"><STRONG><h1 class="text-center"><a href="signin.php">Login</a></h1></strong><STRONG><h1 class="text-center"><a href="signup.php">Register</a></h1></strong></div></div>'
      ;
    }
    else if($_SERVER['REQUEST_METHOD'] != 'POST')
    { 
   $sql = "select distinct n1.nid, n1.title, n1.content, n1.uid, n1.name from
   (select f.uid, f.vstatus, f.tagid, s.stateid, f.fid 
  from users u, state s, filter f, schedule sc, location l
   where  u.state_id = s.stateid AND
   u.id = '".($_SESSION['user_id'])."'
   and s.stateid = f.stateid AND
  u.id = l.uid and 
  f.uid = u.id AND
   f.scheduleid = sc.sid 
   order by f.uid AND
   check_schedule(l.current, sc.type, sc.stdate, sc.enddate, sc.vstart, sc.vend) = 1 AND
   getdistance(l.curr_latitude, l.curr_longitude, f.latitude, f.longitude) <= f.radius) as f1,
   (select DISTINCT n.nid, n.content, n.visible_to, nt.tagid, n.uid, n.title, u1.name
  from note n, tagging nt, schedule sc, location l, users u, users u1
   where n.nid = nt.nid AND
   n.uid = u1.id AND
   u.id = l.uid AND
    u.id = '".($_SESSION['user_id'])."' AND
    n.scheduleid = sc.sid AND
   check_schedule(l.current, sc.type,  sc.vstart, sc.vend,sc.stdate, sc.enddate) = 1 AND
  getdistance(l.curr_latitude, l.curr_longitude, n.latitude, n.longitude) <= n.radius_of_interest) as n1
  
  where ((f1.tagid IS NOT NULL AND n1.tagid IS NOT NULL AND f1.tagid = n1.tagid) OR (f1.tagid IS NULL)) AND check_if_visible(f1.vstatus, n1.visible_to,f1.uid, n1.uid) = 1";
  if($note = mysqli_query($con, $sql))
		{
      if(mysqli_num_rows($note)!=0){
        echo '<br><br><div class="container"><form action="index.php" method="post"><div class="input-group">
        <input type="text" class="form-control" name="sear" placeholder="Search Notes">
        <span class="input-group-btn">
             <button class="btn btn-primary" type="submit" name="search">Search!</button>
        </span>
     </div>
     </form><br><br>';
      
      echo '<div class="row">
      ';
			while ($row=mysqli_fetch_row($note)) {
        
      
        echo '<div class="col-lg-4 mb-4">
                <div class="card h-100">
                  <strong class="card-header">
            ';
        echo $row[1];
        echo '</strong><small><span class="text-muted">&nbsp;&nbsp;&nbsp; Author : ';
        echo $row[4];
        echo '</span></small>
                    <div class="card-body">
          <div class="card-text">';
        echo $row[2];
  ?>    </div>
                    </div>
        
                    <div class="card-footer">
        <a href="comment.php?nid=<?= $row[0] ?>&nu=<?= $row[4] ?>" class="btn btn-primary">Expand</a><?php
        echo '
                    </div>
                  </div>
                
              </div>';
              ?>
        <?php
    }
    
    echo '</div>';
    echo '</div><br>';
    echo ' <div id="default" style="width:50%; height:50%; align:center"></div>';
    $sql = "select distinct n1.title, n1.content, n1.latitude, n1.longitude from
    (select f.uid, f.vstatus, f.tagid, s.stateid, f.fid 
   from users u, state s, filter f, schedule sc, location l
    where  u.state_id = s.stateid AND
    u.id = '".($_SESSION['user_id'])."'
    and s.stateid = f.stateid AND
   u.id = l.uid and 
   f.uid = u.id AND
    f.scheduleid = sc.sid 
    order by f.uid AND
    check_schedule(l.current, sc.type, sc.stdate, sc.enddate, sc.vstart, sc.vend) = 1 AND
    getdistance(l.curr_latitude, l.curr_longitude, f.latitude, f.longitude) <= f.radius) as f1,
    (select DISTINCT n.nid, n.content, n.visible_to, nt.tagid, n.uid, n.title, u1.name, n.latitude, n.longitude
   from note n, tagging nt, schedule sc, location l, users u, users u1
    where n.nid = nt.nid AND
    n.uid = u1.id AND
    u.id = l.uid AND
     u.id = '".($_SESSION['user_id'])."' AND
     n.scheduleid = sc.sid AND
    check_schedule(l.current, sc.type,  sc.vstart, sc.vend,sc.stdate, sc.enddate) = 1 AND
   getdistance(l.curr_latitude, l.curr_longitude, n.latitude, n.longitude) <= n.radius_of_interest) as n1
   
   where ((f1.tagid IS NOT NULL AND n1.tagid IS NOT NULL AND f1.tagid = n1.tagid) OR (f1.tagid IS NULL)) AND check_if_visible(f1.vstatus, n1.visible_to,f1.uid, n1.uid) = 1";
  ;

    if($note = mysqli_query($con, $sql))
    {
      $row=mysqli_fetch_all($note,MYSQLI_NUM);
      $ro = json_encode($row);  
      echo '<script>
          initialize('.$ro. '); </script>';
          
    }
    
    
    ?>

   
 <?php 
 
}
  

else{
  echo '<br><br><div class="container"><strong>Currently, there are no notes availble to display based on your filters</strong>';
}
    }
}


else if($_SERVER['REQUEST_METHOD'] == 'POST'){

  $search = $_POST['sear'];

  $search = mysqli_real_escape_string($con,$search);

  $sql = "select * from (select distinct n1.nid, n1.title, n1.content, n1.uid, n1.name from
   (select f.uid, f.vstatus, f.tagid, s.stateid, f.fid 
  from users u, state s, filter f, schedule sc, location l
   where  u.state_id = s.stateid AND
   u.id = '".($_SESSION['user_id'])."'
   and s.stateid = f.stateid AND
  u.id = l.uid and 
  f.uid = u.id AND
   f.scheduleid = sc.sid 
   order by f.uid AND
   check_schedule(l.current, sc.type, sc.stdate, sc.enddate, sc.vstart, sc.vend) = 1 AND
   getdistance(l.curr_latitude, l.curr_longitude, f.latitude, f.longitude) <= f.radius) as f1,
   (select DISTINCT n.nid, n.content, n.visible_to, nt.tagid, n.uid, n.title, u1.name
  from note n, tagging nt, schedule sc, location l, users u, users u1
   where n.nid = nt.nid AND
   n.uid = u1.id AND
   u.id = l.uid AND
    u.id = '".($_SESSION['user_id'])."' AND
    n.scheduleid = sc.sid AND
   check_schedule(l.current, sc.type,  sc.vstart, sc.vend,sc.stdate, sc.enddate) = 1 AND
  getdistance(l.curr_latitude, l.curr_longitude, n.latitude, n.longitude) <= n.radius_of_interest) as n1
  
  where ((f1.tagid IS NOT NULL AND n1.tagid IS NOT NULL AND f1.tagid = n1.tagid) OR (f1.tagid IS NULL)) AND check_if_visible(f1.vstatus, n1.visible_to,f1.uid, n1.uid) = 1) as final

  where MATCH(content) AGAINST ('".($search)."' IN BOOLEAN MODE)
  ";
  if($note = mysqli_query($con, $sql))
		{
      if(mysqli_num_rows($note)!=0){
        echo '<br><br><div class="container"><div class="container">';
      
      echo '
      <form action="index.php" method="post"><div class="input-group">
        <input type="text" class="form-control" name="sear" placeholder="Search Notes">
        <span class="input-group-btn">
             <button class="btn btn-primary" type="submit" name="search">Search!</button>
        </span>
     </div>
     </form>
     <br>
     
     <div class="card h-100">
            <h4 class="card-header">
    <a href="index.php">
  
    <strong>View Notes</strong></a></h4></div><br>
     </div>
      ';
			while ($row=mysqli_fetch_row($note)) {
        
      
        echo '<div class="col-lg-4 mb-4">
                <div class="card h-100">
                  <strong class="card-header">
            ';
        echo $row[1];
        echo '</strong><small><span class="text-muted">&nbsp;&nbsp;&nbsp; Author : ';
        echo $row[4];
        echo '</span></small>
                    <div class="card-body">
          <div class="card-text">';
        echo $row[2];
  ?>    </div>
                    </div>
        
                    <div class="card-footer">
        <a href="comment.php?nid=<?= $row[0] ?>&nu=<?= $row[4] ?>" class="btn btn-primary">Expand</a><?php
        echo '
                    </div>
                  </div>
                
              </div>';
              ?>
        <?php
    }
    
    echo '</div>';
    echo '</div><br>';
    echo ' <div id="default" style="width:50%; height:50%; align:center"></div>';
    $sql = "select * from (select distinct n1.title, n1.content, n1.latitude, n1.longitude from
    (select f.uid, f.vstatus, f.tagid, s.stateid, f.fid 
   from users u, state s, filter f, schedule sc, location l
    where  u.state_id = s.stateid AND
    u.id = '".($_SESSION['user_id'])."'
    and s.stateid = f.stateid AND
   u.id = l.uid and 
   f.uid = u.id AND
    f.scheduleid = sc.sid 
    order by f.uid AND
    check_schedule(l.current, sc.type, sc.stdate, sc.enddate, sc.vstart, sc.vend) = 1 AND
    getdistance(l.curr_latitude, l.curr_longitude, f.latitude, f.longitude) <= f.radius) as f1,
    (select DISTINCT n.nid, n.content, n.visible_to, nt.tagid, n.uid, n.title, u1.name, n.latitude, n.longitude
   from note n, tagging nt, schedule sc, location l, users u, users u1
    where n.nid = nt.nid AND
    n.uid = u1.id AND
    u.id = l.uid AND
     u.id = '".($_SESSION['user_id'])."' AND
     n.scheduleid = sc.sid AND
    check_schedule(l.current, sc.type,  sc.vstart, sc.vend,sc.stdate, sc.enddate) = 1 AND
   getdistance(l.curr_latitude, l.curr_longitude, n.latitude, n.longitude) <= n.radius_of_interest) as n1
   
   where ((f1.tagid IS NOT NULL AND n1.tagid IS NOT NULL AND f1.tagid = n1.tagid) OR (f1.tagid IS NULL)) AND check_if_visible(f1.vstatus, n1.visible_to,f1.uid, n1.uid) = 1) as final

   where MATCH(content) AGAINST ('".($search)."' IN BOOLEAN MODE)
   ";
  
   ;

    if($note = mysqli_query($con, $sql))
    {
      $row=mysqli_fetch_all($note,MYSQLI_NUM);
      $ro = json_encode($row);  
      echo '<script>
          initialize('.$ro. '); </script>';
          
    }
    
    
    ?>

   
 <?php 
 
  }}}