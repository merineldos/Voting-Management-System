<?php
  require_once('../admin/inc/config.php');
  
  if($isset($_POST['e_id']))
  mysqli_query($db,"INSERT INTO votings(election_id, voter_id,candidate_id,vote_date,vote_time ) VALUES('".$_POST['e_id']."','".$_POST['v_id']."','".$_POST['c_id']."','".$vote_date."','".$vote_time."')") or die(mysqli_error($db));


 ?> 