<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Voters Panel</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Background Styling */
    body {
      font-family: Arial, sans-serif;
      background: #f2f3f5; /* Light grey background */
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Page Container */
    .container {
      max-width: 800px;
    }

    /* Table Container Styling */
    .table-container {
      background-color: #ffffff; /* White background */
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Table Styling */
    .table thead th {
      background-color: #4CAF50; /* Lighter green */
      color: #fff;
      text-align: center;
    }
    .table tbody tr {
      background-color: #f5f5f5; /* Very light grey */
      color: #333;
    }
    .table tbody tr td {
      text-align: center;
      vertical-align: middle;
    }

    /* Candidate Photo Styling */
    .candidate_photo {
      width: 50px;
      height: 50px;
      border-radius: 5px;
      object-fit: cover;
    }

    /* Heading Styling */
    h2 {
      color: #2c3e50;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }

    /* Action Button Styling */
    .btn-vote {
      background-color: #2196F3; /* Lighter blue */
      color: #fff;
      border: none;
      padding: 8px 15px;
      font-size: 14px;
      font-weight: bold;
      border-radius: 20px;
      transition: background-color 0.3s;
    }
    .btn-vote:hover {
      background-color: #1769AA; /* Darker blue on hover */
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Election Details</h2>
  <div class="table-container">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Photo</th>
          <th>Candidate Details</th>
          <th>Votes</th>
         
        </tr>
      </thead>
      <tbody>
        <?php
          // Sample Data (replace this with your database fetch loop)
          $candidates = [
            ["1", "../assets/images/electoral symbols/Screenshot 2024-11-08 124727.png", "Darsana Rajeev", "She is of political Party SDE", "20"],
            ["2", "../assets/images/electoral symbols/Screenshot 2024-11-08 124715.png", "Aditya Mohan", "She is of political Party AFT", "30"],
          ];

          foreach ($candidates as $candidate) {
            echo "<tr>
              <td><img src='{$candidate[1]}' class='candidate_photo' alt='Candidate Photo'></td>
              <td><b>{$candidate[2]}</b><br>{$candidate[3]}</td>
              <td>{$candidate[4]}</td>
              
            </tr>";
          }
        ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.