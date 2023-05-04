<?php
error_reporting(0);
$con = new mysqli('localhost', 'root', '', 'main_db');

if($con->connect_errno > 0){
    die('Unable to connect to database [' . $con->connect_error . ']');
}


ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	$user = "";
}
else {
	header("location: index.php");
}

$u_fname = "";
$u_email = "";
$u_mobile = "";
$u_pass = "";
if (isset($_POST['signup'])) {
//declere veriable
$u_fname = $_POST['first_name'];
$u_email = $_POST['email'];
$u_mobile = $_POST['mobile'];
$u_pass = $_POST['password'];
$u_ac = $_POST['account'];
$u_gender = $_POST['gender'];

$_POST['first_name'] = trim($_POST['first_name']);
try {
	if(empty($_POST['first_name'])) {
		throw new Exception('Fullname can not be empty');
		
	}
	if (is_numeric($_POST['first_name'][0])) {
		throw new Exception('Please write your correct name!');
	}
	if(empty($_POST['email'])) {
		throw new Exception('Email can not be empty');
		
	}
	if(empty($_POST['mobile'])) {
		throw new Exception('Mobile can not be empty');
		
	}
	if(empty($_POST['password'])) {
		throw new Exception('Password can not be empty');
		
	}
	
	
	$check = 0;
	$e_check = $con->query("SELECT email FROM `user` WHERE email='$u_email'");
	$email_check = mysqli_num_rows($e_check);
	if (strlen($_POST['first_name']) >2 && strlen($_POST['first_name']) <16 ) {
		if ($check == 0 ) {
			if ($email_check == 0) {
				if (strlen($_POST['password']) >1 ) {
					$d = date("Y-m-d"); 
					$u_fname = ucwords($_POST['first_name']);
					$u_email = mb_convert_case($u_email, MB_CASE_LOWER, "UTF-8");
					$u_pass = md5($_POST['password']);
					$confirmCode   = mt_rand(100000, 999999);
				
					$msg = "
					
					
					Your activation code: ".$confirmCode."
					Signup email: ".$_POST['email']."
					
					";
					

					$sql = "INSERT INTO `user` (`fullname`,`gender`,`email`,`phone`,`pass`,`type`,`confirmcode`) VALUES ('".$u_fname."','".$u_gender."','".$u_email."','".$u_mobile."','".$u_pass."','".$u_ac."','".$confirmCode."')";

					if($con->query($sql)){
						//success message
					$success_message = '
					<div class="signupform_content"><h2><font face="bookman">Registration successfull!</font></h2>
					<div class="signupform_text" style="font-size: 18px; text-align: center;">
					<font face="bookman">

					</font></div></div>'; 

					}else{
						echo "Error: " . $sql . "<br>" . $con->error;
					}

				
					
					
				}else {
					throw new Exception('Make strong password!');
				}
			}else {
				throw new Exception('Email already taken!');
			}
		}else {
			throw new Exception('Username already taken!');
		}
	}else {
		throw new Exception('Firstname must be 2-15 characters!');
	}
}
catch(Exception $e) {
	$error_message = $e->getMessage();
}
}


?>




<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="css/footer.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/reg.css" rel="stylesheet" type="text/css" media="all" />

	<!-- menu tab link -->
	<link rel="stylesheet" type="text/css" href="css/home.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>
<body class="">
<div>
<div>
		<header class="header">

			<div class="header-cont">

				<?php
					include 'inc/banner.inc.php';
				?>

			</div>
		</header>
		
		<div class="topnav">
			
			<a class="navlink" href="index.php" style="margin: 0px 0px 0px 100px;">Newsfeed</a>
			<a class="navlink" href="search.php">Search Tutor</a>
			<a class="navlink" href="postform.php">Post</a>
		
			<div style="float: right;" >
				<table>
					<tr>
						<?php
								echo '<td>
							<a class="navlink" href="login.php">Login</a>
						</td>
						<td>
							<a class="active navlink" href="registration.php">Register</a>
						</td>';
						?>
						
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="nbody" style="margin: 0px 100px; overflow: hidden;">
		<div class="nfeedleft" style="background-color: unset;">
			<div>
		<div class="testbox">


			<?php
				echo '<div class="signup_error_msg">';
					
						if (isset($error_message)) {echo $error_message;}
						
					
				echo'</div>';
				if(isset($success_message)) {echo $success_message;}
				else echo'
		  			<h1>Registration</h1>
					<form action="" method="post">
				      <hr>
				    <div class="accounttype">
				      <input type="radio" value="teacher" id="radioOne" name="account" checked/>
				      <label for="radioOne" class="radio" chec>As a Teacher</label>
				      <input type="radio" value="student" id="radioTwo" name="account" />
				      <label for="radioTwo" class="radio">As a Student or Parents</label>
				    </div>
				  <hr>
				  <label id="icon" for="name"><i class="icon-envelope "></i></label>
				  <input type="text" name="first_name" id="name" placeholder="Full Name" value="'.$u_fname.'" required/>
				  <label id="icon" for="name"><i class="icon-envelope "></i></label>
				  <input type="text" name="email" id="name" placeholder="Email" value="'.$u_email.'"  required/>
				  <label id="icon" for="name"><i class="icon-envelope "></i></label>
				  <input type="text" name="mobile" id="name" placeholder="Phone" value="'.$u_mobile.'" required/>
				  <label id="icon" for="name"><i class="icon-user"></i></label>
				  <input type="password" name="password" id="name" placeholder="Password" required/>
				  <label id="icon" for="name"><i class="icon-shield"></i></label>
				  <input type="password" name="cpassword" id="name" placeholder="Confirm Password" required/>
				  <div class="gender">
				    <input type="radio" value="male" id="male" name="gender" checked/>
				    <label for="male" class="radio" chec>Male</label>
				    <input type="radio" value="female" id="female" name="gender" />
				    <label for="female" class="radio">Female</label>
				   </div> 
				   <p>By clicking Register, you agree on our <a href="#">terms and condition</a>.</p>
				   <input type="submit" name="signup" class="sub_button" id="submit" value="Registration" required/>
				  </form>'
			?>

		  
		</div>
	</div>
		</div>
		<div class="nfeedright">
			
		</div>
	</div><br><br>

	
	<div>
	<?php
		include 'inc/footer.inc.php';
	?>
	</div>
	</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script  src="js/homemenu.js"></script>
</body>
</html>