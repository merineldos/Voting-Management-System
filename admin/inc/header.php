<?php
session_start();
require_once('config.php');
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    echo "Not authorized"; // Add this line
    echo "<script> location.assign('../logout.php');</script>";
    die;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Online Voting System</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .header-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            margin: 20px;
            padding: 15px 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .logo-container {
            display: flex;
            align-items: center;
        }
        .logo-container img {
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .header-title {
            color: white;
            font-weight: 600;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        .welcome-text {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="header-container">
                    <div class="row align-items-center">
                        <div class="col-1">
                            <div class="logo-container">
                                <img src="../assets/images/loginlogo2 - Copy.jpg" alt="logo" width="60px" height="60px" />
                            </div>
                        </div>
                        <div class="col-11">
                            <h3 class="header-title">ONLINE VOTING MANAGEMENT SYSTEM</h3>
                            <div class="welcome-text">Welcome <?php echo $_SESSION['username']; ?>! Your voice matters. Make it count.</div>
                        </div>    
                    </div>
                </div>
            </div>
        </div>