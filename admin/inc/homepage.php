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

  

    <div class="col-md-12">
      <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
          <h4 class="mb-0">Elections</h4>
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
                      <a href='#' class="btn btn-sm btn-success">View Results</a>
                     
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



<!--
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Election Management System</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <style>
    /* Center Box Styling */
    .center-box {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 520px; /* Set height to fill the viewport */
    }
    .card {
      width: 60%;
      text-align: center;
    }
    /* Welcome and Other Text Styling */
    h4, p {
      margin-bottom: 20px;
    }
  </style>   
</head>
<body>
  
  <div class="container-fluid center-box">
    <div class="card shadow-sm">
      <div class="card-header bg-info text-white">
        <h4 class="mb-0">Welcome to Voting Management System</h4>
      </div>
      <div class="card-body">
      <h4>Transforming Elections: Streamlined, Secure, and Stress-Free!</h4>
        <img src="data:image/jpeg;base64,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">

        <h5>CHOOSE AN OPTION FROM THE NAVBAR</h5>
      </div>
    </div>
  </div>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
-->