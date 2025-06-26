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
      margin: 0 20px 20px 20px;
    }
    .card {
      background: rgba(255, 255, 255, 0.95);
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(10px);
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
    .card-body {
      padding: 25px;
    }
    .form-group label {
      color: #333;
      font-weight: 600;
      margin-bottom: 8px;
    }
    .form-control {
      border: 2px solid rgba(102, 126, 234, 0.1);
      border-radius: 12px;
      padding: 12px 15px;
      background: rgba(255, 255, 255, 0.8);
      transition: all 0.3s ease;
    }
    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
      background: white;
    }
    .btn {
      border-radius: 12px;
      font-weight: 600;
      padding: 12px 30px;
      transition: all 0.3s ease;
      border: none;
    }
    .btn-success {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
    }
    .btn-warning {
      background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
      color: white;
    }
    .btn-warning:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(250, 112, 154, 0.4);
    }
    .btn-danger {
      background: linear-gradient(135deg, #ff6b6b 0%, #ffa500 100%);
    }
    .btn-danger:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
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
    .alert {
      background: rgba(255, 255, 255, 0.9);
      border: none;
      border-radius: 15px;
      margin: 0 20px 20px 20px;
      backdrop-filter: blur(10px);
    }
    .card-body form {
      opacity: 1;
      animation: fadeIn 1s ease-in-out forwards;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
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
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
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
        <div class="card">
          <div class="card-header">
            <h4 class="mb-0">Upcoming Elections</h4>
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