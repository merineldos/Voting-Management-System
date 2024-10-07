<?php
if (isset($_GET['added'])) {
    ?>
    <div class="alert alert-success" role="alert">
        Candidate Added Successfully.
    </div>
    <?php
} else if (isset($_GET['largeFile'])) {
    ?>
    <div class="alert alert-danger" role="alert">
        Candidate Image is too large, please upload a smaller file (up to 2MB).
    </div>
    <?php
} else if (isset($_GET['InvalidFile'])) {
    ?>
    <div class="alert alert-danger" role="alert">
        Invalid File Type.
    </div>
    <?php
} else if (isset($_GET['failed'])) {
    ?>
    <div class="alert alert-danger" role="alert">
        Image Uploading failed!
    </div>
    <?php
}
?>

<div class="row my-3">
    <div class="col-4">
        <h3>Add New Candidate</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <select class="form-control" name="election_id" required>
                    <option value="">Select Election</option>
                    <?php
                    $fetchingElections = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db));
                    $isAnyElectionAdded = mysqli_num_rows($fetchingElections);
                    if ($isAnyElectionAdded > 0) {
                        while ($row = mysqli_fetch_assoc($fetchingElections)) {
                            $election_id = $row['id'];
                            $election_name = $row['election_topic'];
                            $allowed_candidates = $row['no_of_candidates'];
                            $fetchingCandidate = mysqli_query($db, "SELECT * FROM candidate_details WHERE election_id='$election_id'") or die(mysqli_error($db));
                            $added_candidates = mysqli_num_rows($fetchingCandidate);
                            if ($added_candidates < $allowed_candidates) {
                                ?>
                                <option value="<?php echo $election_id; ?>"><?php echo $election_name; ?></option>
                                <?php
                            }
                        }
                    } else {
                        ?>
                        <option value="">Please Add Election First</option>
                        <?php
                    }
                    ?>
                </select>    
            </div> 

            <div class="form-group">
                <input type="text" name="candidate_name" class="form-control" placeholder="Candidate Name" required/>
            </div>

            <div class="form-group">
                <input type="file" name="candidate_photo" class="form-control" placeholder="Candidate Photo" required/>
            </div>

            <div class="form-group">
                <input type="text" name="candidate_details" class="form-control" placeholder="Candidate Details" required/>
            </div>

            <input type="submit" value="Add Candidate" name="add_candidatebtn" class="btn btn-success" />
        </form>    
    </div>  

    <div class="col-8">
        <h3>Candidate Details</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Name</th>
                    <th scope="col">Details</th>
                    <th scope="col">Election</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetchingData = mysqli_query($db, "SELECT * FROM candidate_details") or die(mysqli_error($db));
                $isAnyCandidateAdded = mysqli_num_rows($fetchingData);
                if ($isAnyCandidateAdded > 0) {
                    $sno = 1;
                    while ($row = mysqli_fetch_assoc($fetchingData)) {
                        $election_id = $row['election_id'];
                        $fetchingElectionName = mysqli_query($db, "SELECT * FROM elections WHERE id='$election_id'") or die(mysqli_error($db));
                        $election_row = mysqli_fetch_assoc($fetchingElectionName);
                        
                        // Check if the election was found
                        if ($election_row) {
                            $election_name = $election_row['election_topic'];
                        } else {
                            $election_name = "Unknown Election"; // Handle case where election is not found
                        }

                        $candidate_photo = $row['candidate_photo'];
                        ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><img src="<?php echo $candidate_photo; ?>" alt="Candidate Photo" style="width: 50px; height: 50px;" /></td>
                            <td><?php echo $row['candidate_name']; ?></td>
                            <td><?php echo $row['candidate_details']; ?></td>
                            <td><?php echo $election_name; ?></td>
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
                        <td colspan="6" class="text-center">No Candidates Added Yet</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>    
</div>    

<?php
if (isset($_POST['add_candidatebtn'])) {
    $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
    $candidate_name = mysqli_real_escape_string($db, $_POST['candidate_name']);
    $candidate_details = mysqli_real_escape_string($db, $_POST['candidate_details']);    

    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");
    $targetted_folder = "../assets/images/candidate_photos/";
    $candidate_photo = $targetted_folder . rand(111111111, 999999999) . $_FILES['candidate_photo']['name'];
    $candidate_photo_tmp_name = $_FILES['candidate_photo']['tmp_name'];
    $candidate_photo_type = strtolower(pathinfo($candidate_photo, PATHINFO_EXTENSION));  
    $allowed_types = array("jpg", "jpeg", "png");
    $image_size = $_FILES['candidate_photo']['size'];

    if ($image_size < 2000000) {
        if (in_array($candidate_photo_type, $allowed_types)) {
            if (move_uploaded_file($candidate_photo_tmp_name, $candidate_photo)) {
                mysqli_query($db, "INSERT INTO candidate_details (election_id, candidate_name, candidate_details, candidate_photo, inserted_by, inserted_on) 
                VALUES ('$election_id', '$candidate_name', '$candidate_details', '$candidate_photo', '$inserted_by', '$inserted_on')") 
                or die(mysqli_error($db));
                echo "<script> location.assign('index.php?addCandidatePage=1&added=1');</script>";
            } else {
                echo "<script> location.assign('index.php?addCandidatePage=1&failed=1');</script>";
            }
        } else {
            echo "<script> location.assign('index.php?addCandidatePage=1&InvalidFile=1');</script>";
        }
    } else {
        echo "<script> location.assign('index.php?addCandidatePage=1&largeFile=1');</script>";
    }
    die;
}
?>
