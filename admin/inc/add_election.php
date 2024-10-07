<?php
if (isset($_GET['added'])) {
    ?>
    <div class="alert alert-success" role="alert">
        This is a success alert—check it out!
    </div>
    <?php
}
?>

<div class="row my-3">
    <div class="col-4">
        <h3>Add New Election</h3>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="election_topic" class="form-control" placeholder="Election Topic" required/>
            </div>

            <div class="form-group">
                <input type="number" name="no_of_candidates" class="form-control" placeholder="No Of Candidates" required/>
            </div>

            <div class="form-group">
                <input type="text" onfocus="this.type='date'" name="starting_date" class="form-control" placeholder="Starting Date" required/>
            </div>

            <div class="form-group">
                <input type="text" onfocus="this.type='date'" name="ending_date" class="form-control" placeholder="Ending Date" required/>
            </div>

            <input type="submit" name="add_electionbutton" class="btn btn-success" value="Add Election"/>
        </form>    
    </div>  

    <div class="col-8">
        <h3>Upcoming Elections</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Election Name</th>
                    <th scope="col">Candidates</th>
                    <th scope="col">Starting Date</th>
                    <th scope="col">Ending Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetchingData = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db));
                $isAnyElectionAdded = mysqli_num_rows($fetchingData);
                if ($isAnyElectionAdded > 0) {
                    $sno = 1;
                    while ($row = mysqli_fetch_assoc($fetchingData)) {
                        ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><?php echo $row['election_topic']; ?></td>
                            <td><?php echo $row['no_of_candidates']; ?></td>
                            <td><?php echo $row['starting_date']; ?></td>
                            <td><?php echo $row['ending_date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <a href='#' class="btn btn-sm btn-warning">Edit</a>
                                <a href='#' class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7" class="text-center">No Election Added Yet</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>    
</div>    

<?php
if (isset($_POST['add_electionbutton'])) {
    $election_topic = mysqli_real_escape_string($db, $_POST['election_topic']);
    $no_of_candidates = mysqli_real_escape_string($db, $_POST['no_of_candidates']);
    $starting_date = mysqli_real_escape_string($db, $_POST['starting_date']);    
    $ending_date = mysqli_real_escape_string($db, $_POST['ending_date']);
    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");

    $date1 = date_create("$inserted_on");
    $date2 = date_create("$starting_date");
    $diff = date_diff($date1, $date2);
    if ($diff->format("%R%a") > 0) {
        $status = "active";
    } else {
        $status = "inactive";
    }

    mysqli_query($db, "INSERT INTO `elections`(`election_topic`, `no_of_candidates`, `starting_date`, `ending_date`, `inserted_by`, `inserted_on`, `status`) 
                       VALUES ('$election_topic','$no_of_candidates','$starting_date','$ending_date','$inserted_by','$inserted_on','$status')") 
                       or die(mysqli_error($db));

    ?>
    <script> location.assign("index.php?AddElectionPage=1&added=1");</script>
    <?php
}
?>
