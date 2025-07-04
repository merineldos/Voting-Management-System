<!DOCTYPE html>
<html>
<head>
    <title>Login - Voting System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/login.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7f6;
            height: 100vh;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .logo {
            height: 100px;
            width: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .btn-primary {
            background-color: #3498db;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .input-group-text {
            background-color: #3498db;
            color: white;
            border: none;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            color: #2980b9;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center h-100">
        <div class="card col-md-6 col-lg-4">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <img src="./assets/images/loginlogo2 - Copy.jpg" class="logo" alt="Logo">
                    <h4 class="mt-3">Voting System</h4>
                </div>

                <?php
                require_once('./admin/inc/config.php'); // Database config file

                if (isset($_GET['sign-up'])) {
                ?>
                    <!-- Sign Up Form -->
                    <form method="POST" action="">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="su_username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="text" name="su_contact" class="form-control" placeholder="Contact" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="su_password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="su_repassword" class="form-control" placeholder="Confirm Password" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <button type="submit" name="sign_up_button" class="btn btn-primary">Create Account</button>
                        <div class="text-center mt-3">
                            Already have an account? <a href="index.php">Sign In</a>
                        </div>
                    </form>
                <?php
                } else {
                ?>
                    <!-- Login Form -->
                    <form method="POST">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="Username" required/>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Password" required/>
                        </div>
                        <button type="submit" name="login_button" class="btn btn-primary">Sign In</button>
                        <div class="text-center mt-3">
                            Don't have an account? <a href="index.php?sign-up=1">Sign Up</a>
                        </div>
                        <div class="text-center mt-2">
                            <a href="?sign-up=1">Forgot your password?</a>
                        </div>
                    </form>
                <?php
                }

                // Status messages
                if (isset($_GET['registered'])) {
                    echo '<div class="alert alert-success text-center mt-3">Registration Successful</div>';
                } else if (isset($_GET['invalid'])) {
                    echo '<div class="alert alert-danger text-center mt-3">Passwords do not match</div>';
                } else if (isset($_GET['not_registered'])) {
                    echo '<div class="alert alert-warning text-center mt-3">Sorry, you are not registered</div>';
                } else if (isset($_GET['invalid_access'])) {
                    echo '<div class="alert alert-danger text-center mt-3">Invalid username or password</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once('./admin/inc/config.php');

if (isset($_POST['sign_up_button'])) {
    $su_username = mysqli_real_escape_string($db, $_POST['su_username']);
    $su_contact = mysqli_real_escape_string($db, $_POST['su_contact']);
    $su_password = password_hash($_POST['su_password'], PASSWORD_DEFAULT);
    $su_repassword = password_hash($_POST['su_repassword'], PASSWORD_DEFAULT); // Not necessary to hash again

    if ($_POST['su_password'] === $_POST['su_repassword']) {
        // Insert user data into database as a voter
        $query = "INSERT INTO users (username, contact_no, password, user_role) 
                  VALUES ('$su_username', '$su_contact', '$su_password', 'voter')";
        
        if (mysqli_query($db, $query)) {
            // Redirect after successful registration
            echo '<script>location.assign("index.php?sign-up=1&registered=1");</script>';
        } else {
            // Handle query failure
            die("Query failed: " . mysqli_error($db));
        }
    } else {
        // Passwords do not match, redirect with an error
        echo '<script>location.assign("index.php?sign-up=1&invalid=1");</script>';
    }
}

if (isset($_POST['login_button'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']); 
    $password = mysqli_real_escape_string($db, $_POST['password']);

    $fetchingData = mysqli_query($db, "SELECT * FROM users WHERE username='" . $username . "'") or die(mysqli_error($db));
    if (mysqli_num_rows($fetchingData) > 0) {
        $data = mysqli_fetch_assoc($fetchingData);
        if ($username == $data['username'] && password_verify($password, $data['password'])) {
            session_start();
            $_SESSION["user_role"] = $data['user_role'];
            $_SESSION['username'] = $data['username'];

            if ($data['user_role'] == 'admin') {
                $_SESSION['key'] = $data['adminkey'];
                echo '<script>location.assign("admin/index.php?homepage=1");</script>';
            } else {
                $_SESSION['key'] = $data['voterkey'];
                echo '<script>location.assign("voter/index.php");</script>';
            }
        } else {
            echo '<script>location.assign("index.php?invalid_access=1");</script>';
        }
    } else {
        echo '<script>location.assign("index.php?sign-up=1&not_registered=1");</script>';
    }
}
?>