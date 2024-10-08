<?php
   require_once("inc/header.php");
   require_once("inc/navigation.php");
?>

<div class="row my-3">
    <div class="col-12">
        <h3> Voters Panel</h3>

        <?php
          $fetchingActiveElection = mysqli_query($db, "SELECT * FROM elections WHERE status='active'") or die(mysqli_error($db));
          $totalActiveElements = mysqli_num_rows($fetchingActiveElection);

          if ($totalActiveElements > 0) {
              while ($data = mysqli_fetch_assoc($fetchingActiveElection)) {
                  $election_id = $data['id'];
                  $election_topic = $data['election_topic'];
              }
        ?>
        
        <table class="table">
            <thead>
                <tr>
                    <th colspan="4" class="bg-green text-black">
                        <h5>ELECTION TOPIC: <?php echo strtoupper($election_topic); ?></h5>
                    </th>
                </tr> 
                <tr>
                    <th>Photo</th> 
                    <th>Candidate Details</th> 
                    <th># of Votes</th> 
                    <th>Action</th> 
                </tr>    
            </thead>  
            <tbody>
                
                <?php
                    $fetchingCandidates=mysqli_query($db,"select * from candidate_details where election_id='".$election_id ."'") or die(mysqli_error($db));

                    
                    while($candidateData=mysqli_fetch_assoc($fetchingCandidates))
                    {
                        $candidate_id=$candidateData['id'];
                        $candidate_photo=$candidateData['candidate_photo'];

                        $fetchingvotes=mysqli_query($db ,"select * from votings where candidate_id='".$candidate_id ."'")or die(mysqli_error($db));
                        $totalvotes=mysqli_num_rows($fetchingvotes);

                        
    
                        ?>    
                        <tr>
                            <td> 
                                <img src="<?php echo $candidate_photo; ?>" class="candidate_photo">
                            </td>
                            <td> 
                                <?php 
                                    echo "<b>" . $candidateData['candidate_name'] . "</b><br/>" . $candidateData['candidate_details']; 
                                ?>
                            </td>
                            <td>
                                <?php echo $totalvotes; ?>
                            </td>
                            <td> 
                                <button class="btn btn-md btn-success" 
                                    onClick="Castvote(<?php echo $election_id; ?>,<?php echo $candidate_id; ?>,<?php echo $_SESSION['user_id']; ?>)">
                                    Vote
                                </button>
                            </td>
                        </tr> 
                        <?php
                        


                    }
                ?>
            </tbody>  

        </table> 
        
        <?php   
          } else 
          {
              echo "No active election";
          }
        ?>

    </div>
</div>
<script>
    const Castvote = (e_id, c_id, v_id) => {
    $.ajax({
        type: "POST",
        url: "inc/ajaxCalls.php",
        data: "e_id=" + e_id + "&c_id=" + c_id + "&v_id=" + v_id,
        success: function(response) {
            if (response == "success") {
                location.assign("index.php?votecasted=1");
            } else {
                location.assign("index.php?votenotcasted=1");
            }
        }
    });
};

</script>    

<?php
   require_once("inc/footer.php");
?>
