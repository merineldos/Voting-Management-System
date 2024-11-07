<?php



if (isset($_POST['add_candidatebtn'])) {
    $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
    $candidate_name = mysqli_real_escape_string($db, $_POST['candidate_name']);
    $candidate_details = mysqli_real_escape_string($db, $_POST['candidate_details']);

    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");
    $target_folder = "assets/images/candidate_photos/";
    $candidate_photo = $target_folder . rand(111111111, 999999999) . $_FILES['candidate_photo']['name'];
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
                echo "<script>location.href='index.php?AddCandidatePage=1';</script>";
            } else {
                echo "<script>alert('Image Upload Failed! Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Invalid File Type! Only JPG, JPEG, PNG allowed.');</script>";
        }
    } else {
        echo "<script>alert('Image size exceeds the maximum allowed size (2MB).');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Candidate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <!-- Add New Candidate Form -->
        <div class="col-md-5 mb-5 shadow p-4 rounded bg-light animate__animated animate__fadeInLeft">
            <h3 class="mb-4 text-center text-success">Add New Candidate</h3>
            <form id="addCandidateForm" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <select class="form-select" name="election_id" required>
                        <option value="">Select Election</option>
                        <?php
                        $elections = mysqli_query($db, "SELECT * FROM elections");
                        while ($row = mysqli_fetch_assoc($elections)) {
                            echo "<option value='" . $row['id'] . "'>" . $row['election_topic'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="text" name="candidate_name" class="form-control" placeholder="Candidate Name" required />
                </div>
                <div class="mb-3">
                    <textarea name="candidate_details" class="form-control" placeholder="Candidate Details" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="candidatePhoto" class="form-label">Candidate Photo (Max 2MB, JPG, JPEG, PNG)</label>
                    <input type="file" name="candidate_photo" class="form-control" required />
                </div>
                <div class="text-end">
                    <button type="submit" name="add_candidatebtn" class="btn btn-success">Add Candidate</button>
                </div>
            </form>
        </div>

        <!-- Candidate List Table -->
        <div class="col-md-7 mb-5 shadow p-4 rounded bg-light animate__animated animate__fadeInRight">
            <h3 class="mb-4 text-center text-success">Candidate Details</h3>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="table-success">
                        <th>S.No</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Details</th>
                        <th>Election</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="candidateTableBody">
                    <?php
                    $candidates = mysqli_query($db, "SELECT * FROM candidate_details");
                    if (mysqli_num_rows($candidates) > 0) {
                        $sno = 1;
                        while ($row = mysqli_fetch_assoc($candidates)) {
                            $election_id = $row['election_id'];
                            $election_query = mysqli_query($db, "SELECT election_topic FROM elections WHERE id='$election_id'");
                            $election_name = mysqli_fetch_assoc($election_query)['election_topic'];
                            
                          

                            echo "
                                <tr>
                                    <td>" . $sno++ . "</td>
                                    <td><img src='" . $row['candidate_photo'] . "' class='rounded-circle' width='50' height='50'></td>
                                    <td>" . $row['candidate_name'] . "</td>
                                    <td>" . $row['candidate_details'] . "</td>
                                    <td>" . $election_name . "</td>
                                    <td>
                                        <button class='btn btn-sm btn-warning'>Edit</button>
                                        <button class='btn btn-sm btn-danger'>Delete</button>
                                    </td>
                                </tr>
                            ";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No Candidates Added Yet</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#addCandidateForm').on('submit', function(e) {
            const fileInput = $('input[type="file"]')[0].files[0];
            if (fileInput && fileInput.size > 2000000) {
                alert('Image size exceeds the 2MB limit.');
                e.preventDefault();
            }
        });
    });
</script>

</body>
</html>
