<!DOCTYPE html>
<html>

<head>
    <title>Login - Voting System</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="./assets/images/loginlogo2 - Copy.jpg" class="brand_logo" alt="Logo">
                    </div>
                </div>

                <?php
                if (isset($_GET['sign-up'])) {
                ?>
                    <div class="d-flex justify-content-center form_container">
                        <form method="POST" action="">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="su_username" class="form-control input_user" placeholder="Username" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="text" name="su_contact" class="form-control input_pass" placeholder="Contact" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="su_password" class="form-control input_pass" placeholder="Password" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="su_repassword" class="form-control input_pass" placeholder="ReType Password" required>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customControlInline">
                                    <label class="custom-control-label textwhite" for="customControlInline">Remember me</label>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-3 login_container">
                                <button type="submit" name="sign_up_button" class="btn login_btn">Sign Up</button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-center links textwhite">
                             Already Created Account? <a href="index.php" class="ml-2">Sign In</a>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <div class="d-flex justify-content-center form_container">
                        <form method="POST">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="username" class="form-control input_user" placeholder="Username" required/>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="password" class="form-control input_pass" placeholder="Password" required/>>
                            </div>
                            
                            <div class="d-flex justify-content-center mt-3 login_container">
                                <button type="submit" name="login_button" class="btn login_btn">Sign In</button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-center links">
                            Don't have an account? <a href="index.php?sign-up=1" class="ml-2">Sign Up</a>
                        </div>
                        <div class="d-flex justify-content-center links textwhite">
                            <a href="?sign-up=1">Forgot your password?</a>
                        </div>
                    </div>
                <?php
                }
                ?>
				   <?php
				if (isset($_GET['registered']))
				 {
				?>
					<div class="bg-white text-success text-center my-3">
						<div class="registrationdone" >
							<h3>Registration Successful</h3>
						</div>
					</div>
				<?php
				}
				else if (isset($_GET['invalid']))
				{
				?>
					<div class="bg-white text-danger text-center my-3 ">
						<div class="registrationfailed" >
							<h3>Passwords do not match</h3>
						</div>
					</div>

				
				
              <?php
		 	   }
			   else if (isset($_GET['not_registered']))
			   {
				  ?>
				      <div class="bg-white text-warning text-center my-3 ">
						<div class="registrationfailed" >
							<h3>Sorry You Are Not Registered</h3>
						</div>
					</div>
				      
				  <?php
			   }

			   else if (isset($_GET['invalid_access']))
			   {
				  ?>
				      <div class="bg-white text-danger text-center my-3 ">
						<div class="registrationfailed" >
							<h3>Invalid username or password</h3>
						</div>
					</div>
				      
				  <?php
			   }
		
			?>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
</body>

</html>

<?php
require_once('./admin/inc/config.php');
if (isset($_POST['sign_up_button']))
{
    $su_username = mysqli_real_escape_string($db, $_POST['su_username']);
    $su_contact = mysqli_real_escape_string($db, $_POST['su_contact']);
    $su_password = mysqli_real_escape_string($db,sha1( $_POST['su_password']));
    $su_repassword = mysqli_real_escape_string($db,sha1( $_POST['su_repassword']));

     if ($su_password === $su_repassword)
	   {
          // Insert user data into database
          $query = "INSERT INTO users (username, contact_no, password, user_role) 
                  VALUES ('$su_username', '$su_contact', '$su_password', 'user')";
        
          if (mysqli_query($db, $query)) 
		  {
            // Redirect after successful registration
            echo '<script>location.assign("index.php?sign-up=1&registered=1");</script>';
          } 
		  else 
		  {
            // Handle query failure
            die("Query failed: " . mysqli_error($db));
          }
        } 
	   else
	   {
        // Passwords do not match, redirect with an error
        echo '<script>location.assign("index.php?sign-up=1&invalid=1");</script>';
       }
}

else if (isset($_POST['login_button']))
{
	$username=mysqli_real_escape_string($db,$_POST['username']); 
	$password=mysqli_real_escape_string($db,sha1($_POST['password']));

	$fetchingData=mysqli_query($db,"SELECT * FROM users WHERE username='".$username ."'") or die (mysqli_error($db));
    if (mysqli_num_rows($fetchingData)>0)
	{
		$data=mysqli_fetch_assoc($fetchingData);
		if ($username==$data['username'] AND $password==$data['password'])
		{
            session_start();
			$_SESSION["user_role"]=$data['user_role'];
			$_SESSION['username']=$data['username'];
			

			if ($data['user_role']=='admin')
			{
				$_SESSION['key']=$data['adminkey'];
				?>
				<script> location.assign("admin/index.php?homepage=1");</script>
				<?php
			}
			else 
			{
				$_SESSION['key']=$data['voterkey'];
				?>
				<script> location.assign("voter/index.php");</script>
				<?php
		    }
		}	
		else
		{
			?>
              <script> location.assign("index.php?invalid_access=1");</script>
		    <?php

		}
	}
	else
	{
		?>
              <script> location.assign("index.php?sign-up=1$not_registered=1");</script>
		<?php

	}

}


?>
