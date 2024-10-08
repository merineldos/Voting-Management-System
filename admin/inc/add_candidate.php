<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Candidate</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <!-- Animate.css for animations -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

  <style>
    .add-candidate-form, 
    .candidate-details-table {
      background: #f8f9fa;
      border-radius: 15px;
      transition: transform 0.3s ease-in-out;
    }

    .add-candidate-form:hover, 
    .candidate-details-table:hover {
      transform: translateY(-10px);
    }

    table img {
      border-radius: 50%;
      transition: transform 0.3s ease;
    }

    table img:hover {
      transform: scale(1.2);
    }

    .btn-warning, 
    .btn-danger {
      transition: background-color 0.3s ease;
    }

    .btn-warning:hover {
      background-color: #ffcc00;
    }

    .btn-danger:hover {
      background-color: #ff3300;
    }
  </style>
</head>
<body>
  <?php
  if (isset($_GET['added'])) {
    ?>
    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
      Candidate Added Successfully!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
  } else if (isset($_GET['largeFile'])) {
    ?>
    <div class="alert alert-danger alert-dismissible fade show animate__animated animate__shakeX" role="alert">
      Candidate Image is too large (max 2MB). Please upload a smaller file.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
  } else if (isset($_GET['InvalidFile'])) {
    ?>
    <div class="alert alert-danger alert-dismissible fade show animate__animated animate__shakeX" role="alert">
      Invalid File Type. Only JPG, JPEG, and PNG allowed.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
  } else if (isset($_GET['failed'])) {
    ?>
    <div class="alert alert-danger alert-dismissible fade show animate__animated animate__shakeX" role="alert">
      Image Uploading failed! Please try again.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
  }
  ?>

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6 mb-5 shadow-lg p-4 rounded animate__animated animate__fadeInLeft add-candidate-form bg-white">
        <h3 class="mb-4 text-center text-primary">Add New Candidate</h3>
        <form method="POST" enctype="multipart/form-data">
          <div class="form-group mb-3">
            <select class="form-control form-control-lg" name="election_id" required>
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
          <div class="form-group mb-3">
            <input type="text" name="candidate_name" class="form-control form-control-lg" placeholder="Candidate Name" required />
          </div>
          <div class="form-group mb-3">
            <input type="file" name="candidate_photo" class="form-control form-control-lg" placeholder="Candidate Photo" required />
            <small class="text-muted">Max file size: 2MB. Allowed types: JPG, JPEG, PNG.</small>
          </div>
          <div class="form-group mb-4">
            <textarea name="candidate_details" class="form-control form-control-lg" placeholder="Candidate Details" rows="3" required></textarea>
          </div>
          <input type="submit" value="Add Candidate" name="add_candidatebtn" class="btn btn-primary btn-lg w-100" />
        </form>
      </div>

      <div class="col-md-6 mb-5 shadow-lg p-4 rounded bg-white animate__animated animate__fadeInRight candidate-details-table">
        <h3 class="mb-4 text-center text-success">Candidate Details</h3>
        <table class="table table-hover table-striped">
          <thead class="table-success">
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

                if ($election_row) {
                  $election_name = $election_row['election_topic'];
                } else {
                  $election_name = "Unknown Election";
                }

                $candidate_photo = $row['candidate_photo'];
                ?>
                <tr>
                  <td><?php echo $sno++; ?></td>
                  <td><img src="<?php echo $candidate_photo; ?>" alt="Candidate Photo" class="rounded-circle" style="width: 50px; height: 50px;" /></td>
                  <td><?php echo $row['candidate_name']; ?></td>
                  <td><?php echo $row['candidate_details']; ?></td>
                  <td><?php echo $election_name; ?></td>
                  <td>
                    <a href='#' class="btn btn-sm btn-warning animate__animated animate__pulse">Edit</a>
                    <a href='#' class="btn btn-sm btn-danger animate__animated animate__shakeX">Delete</a>
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
  </div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
