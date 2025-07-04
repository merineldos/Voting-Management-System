<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Election Management System</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <style>
    .content-container {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      margin: 0 20px 20px 20px;
      padding: 25px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    .card {
      background: rgba(255, 255, 255, 0.95);
      border: none;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    .card-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
      border: none;
      padding: 20px 25px;
    }
    .card-header h4 {
      color: white;
      font-weight: 600;
      margin: 0;
    }
    .table {
      margin: 0;
    }
    .table thead th {
      background: rgba(102, 126, 234, 0.1);
      border: none;
      color: #333;
      font-weight: 600;
      padding: 15px;
    }
    .table tbody td {
      border: none;
      padding: 15px;
      vertical-align: middle;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    .table tbody tr:hover {
      background: rgba(102, 126, 234, 0.05);
    }
    .badge {
      padding: 8px 15px;
      border-radius: 20px;
      font-weight: 500;
      animation: none;
    }
    .badge-success {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      border: none;
    }
    .badge-danger {
      background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
      border: none;
    }
    .btn {
      border-radius: 10px;
      font-weight: 500;
      padding: 8px 20px;
      transition: all 0.3s ease;
      border: none;
    }
    .btn-success {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
    }
    .alert {
      background: rgba(255, 255, 255, 0.9);
      border: none;
      border-radius: 15px;
      margin: 0 20px 20px 20px;
      backdrop-filter: blur(10px);
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

  <div class="content-container">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="mb-0">Elections</h4>
        </div>
        <div class="card-body p-0">
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
                      <a href='#' class="btn btn-success">View Results</a>
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