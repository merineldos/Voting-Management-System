<?php


error_reporting(E_ERROR | E_PARSE);

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
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
            overflow: hidden;
        }
        
        .form-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
        }
        
        .table-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        .form-card h3, .table-card h3 {
            color: #fff;
            font-weight: 600;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            color: #fff;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
            color: #fff;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .form-select option {
            background: #667eea;
            color: #fff;
        }
        
        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(238, 90, 36, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(238, 90, 36, 0.6);
            background: linear-gradient(45deg, #ee5a24, #ff6b6b);
        }
        
        .table {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .table thead th {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-weight: 600;
            border: none;
            padding: 15px;
            text-align: center;
        }
        
        .table tbody td {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.01);
            transition: all 0.3s ease;
        }
        
        .btn-warning {
            background: linear-gradient(45deg, #ffa726, #ff9800);
            border: none;
            border-radius: 20px;
            padding: 6px 15px;
            color: #fff;
            font-weight: 500;
            margin: 2px;
            transition: all 0.3s ease;
        }
        
        .btn-warning:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.4);
            color: #fff;
        }
        
        .btn-danger {
            background: linear-gradient(45deg, #f44336, #e53935);
            border: none;
            border-radius: 20px;
            padding: 6px 15px;
            color: #fff;
            font-weight: 500;
            margin: 2px;
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
            color: #fff;
        }
        
        .candidate-photo {
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .candidate-photo:hover {
            transform: scale(1.1);
            border-color: rgba(255, 255, 255, 0.6);
        }
        
        .animate__animated {
            animation-duration: 0.8s;
        }
        
        @media (max-width: 768px) {
            .form-card, .table-card {
                padding: 20px;
                margin-bottom: 15px;
            }
            
            .table-responsive {
                border-radius: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="main-container">
        <div class="row p-4">
            <!-- Add New Candidate Form -->
            <div class="col-md-5 mb-4">
                <div class="form-card animate__animated animate__fadeInLeft">
                    <h3>Add New Candidate</h3>
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
                        <div class="mb-4">
                            <label for="candidatePhoto" class="form-label">Candidate Photo (Max 2MB, JPG, JPEG, PNG)</label>
                            <input type="file" name="candidate_photo" class="form-control" required />
                        </div>
                        <div class="text-center">
                            <button type="submit" name="add_candidatebtn" class="btn btn-primary">Add Candidate</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Candidate List Table -->
            <div class="col-md-7 mb-4">
                <div class="table-card animate__animated animate__fadeInRight">
                    <h3>Candidate Details</h3>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
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
                                                <td><img src='" . $row['candidate_photo'] . "' class='candidate-photo' width='50' height='50'></td>
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