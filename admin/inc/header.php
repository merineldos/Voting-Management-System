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
</head>
<body>
    <div class="container-fluid">
        
        <div class="row bg-black text-black " >
            <div class="col-1">
                <img src="../assets/images/loginlogo2 - Copy.jpg" alt="logo" width="80px" />
            </div>
            <div class="col-11 my-auto">
                <h3 style="margin: 0;">ONLINE VOTING MANAGEMENT SYSTEM - <small> Welcome <?php echo $_SESSION['username']; ?></small></h3>
            </div>    
        </div>
