<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Election Management System</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <style>
    /* Add New Election Form Animation */
    .card-body form {
      opacity: 0;
      animation: fadeIn 1s ease-in-out forwards;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    /* Highlight on Focus */
    .form-group input:focus {
      border-color: #33b5e5; /* Adjust highlight color if needed */
    }

    /* Upcoming Elections Table Enhancements */
    .table tbody tr:hover {
      background-color: rgba(0, 0, 0, 0.05);
    }

    /* Status Badge Animation */
    .badge {
      animation: bounce 1s ease-in-out infinite;
    }

    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-3px); }
    }
  </style>
</head>
<body>
  <?php
  // Include your database connection here
  include 'config.php';

  // Check if election was added successfully
  if (isset($_GET['added'])) {
    ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Success!</strong> Election added successfully.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php
  }
  ?>

  <div class="row my-4">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
          <h4 class="mb-0">Add New Election</h4>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="form-group">
              <label for="election_topic">Election Topic</label>
              <input type="text" name="election_topic" class="form-control" placeholder="Enter Election Topic" required/>
            </div>

            <div class="form-group">
              <label for="no_of_candidates">No. of Candidates</label>
              <input type="number" name="no_of_candidates" class="form-control" placeholder="Enter No. of Candidates" required/>
            </div>

            <div class="form-group">
              <label for="starting_date">Starting Date</label>
              <input type="text" onfocus="this.type='date'" name="starting_date" class="form-control" placeholder="Select Starting Date" required/>
            </div>

            <div class="form-group">
              <label for="ending_date">Ending Date</label>
              <input type="text" onfocus="this.type='date'" name="ending_date" class="form-control" placeholder="Select Ending Date" required/>
            </div>

            <button type="submit" name="add_electionbutton" class="btn btn-success btn-block">Add Election</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
          <h4 class="mb-0">Upcoming Elections</h4>
        </div>
        <div class="card-body">
          <table class="table table-hover">
            <thead class="thead-dark">
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
              // Fetch election data from the database
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
                    <td><?php echo date('F j, Y', strtotime($row['starting_date'])); ?></td>
                    <td><?php echo date('F j, Y', strtotime($row['ending_date'])); ?></td>
                    <td>
                      <span class="badge <?php echo $row['status'] == 'active' ? 'badge-success' : 'badge-danger'; ?>">
                        <?php echo ucfirst($row['status']); ?>
                      </span>
                    </td>
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
    </div>
  </div>

  <?php
  // Process form submission
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
    $status = $diff->format("%R%a") > 0 ? "active" : "inactive";

    mysqli_query($db, "INSERT INTO `elections`(`election_topic`, `no_of_candidates`, `starting_date`, `ending_date`, `inserted_by`, `inserted_on`, `status`)
                                  VALUES ('$election_topic','$no_of_candidates','$starting_date','$ending_date','$inserted_by','$inserted_on','$status')")
                                  or die(mysqli_error($db));

    echo "<script> location.assign('index.php?AddElectionPage=1&added=1');</script>";
  }
  ?>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>