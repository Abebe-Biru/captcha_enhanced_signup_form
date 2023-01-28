<?php 
require_once("connect.php");
if($con->connect_error) echo "Error occurred while connecting.";
$name = $username = $email = $captcha = NULL;
if(isset($_POST['captcha_btn'])){
	$table = 'users';
	$nmax = 32;
	$umax = 16;
	$salt1 = "fi4e8ab1h09";
	$salt2 = "a0jf34hr9zy";
	$salt3 = "ny7e34lp10q";


	$name = $_POST['name'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];
	$email = $_POST['email'];
	function password_salted($salt1, $salt2, $salt3, $username, $password){
		$password = md5($salt1.$password.$salt2.$username.$salt3);
		return $password;
	}
	$password_salted = password_salted($salt1, $salt2, $salt3, $username, $password);
	$confirm_password_salted = password_salted($salt1, $salt2, $salt3, $username, $confirm_password);
	//file input extraction from user and validation
	$name_on_client = $_FILES['userfile']['name'];
	$name_on_server = $_FILES['userfile']['tmp_name'];
	$input_file_type = $_FILES['userfile']['type'];
	$error_value = $_FILES['userfile']['error'];

	$type_explode_array = explode('/', $input_file_type);
	$input_file_type_extension = end($type_explode_array);
	$valid_extension_array = ['1'=> 'jpeg', 'png'];
	$is_match_valid = array_search($input_file_type_extension, $valid_extension_array);
	$file_uploaded_once = scandir('valid_files');
	$is_uploaded_once = array_search($name_on_client, $file_uploaded_once);


// script to fetch captcha code from database
	$id = $_POST['id'];
	$captcha = $_POST['captcha'];
	$stmt = $con->prepare("SELECT `code` FROM captcha_tbl WHERE `id` = ? ");
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$stmt->bind_result($code);
	$stmt->fetch();
	$stmt->close();


  
  if(empty($name)) 
    echo "<div style=\"color:red\">Name field cannot be empty.</div>";
  elseif(empty($username)) 
    echo "<div style=\"color:red\">Username field cannot be empty.</div>";
  elseif(empty($password)) 
    echo "<div style=\"color:red\">Password field cannot be empty.</div>";
  elseif(empty($email)) 
    echo "<div style=\"color:red\">Email field cannot be empty.</div>";
  elseif($password != $confirm_password)
    echo "<div style=\"color:red\">Confirmation does not match.</div>"; 
//validating the form input 
  elseif($error_value === 4)
    echo "<div style=\"color:red\">File field cannot be empty.</div>";
  elseif(empty($captcha)) 
    echo "<div style=\"color:red\">Captcha field cannot be empty.</div>";
  elseif($error_value === 2)
    echo "<div style=\"color:red\">File of size more than 1MB cannot be entered.</div>";
  elseif(!$is_match_valid)
    echo "<div style=\"color:red\">File of type different from jpeg or png is not supported.</div>";
  elseif($is_uploaded_once)
    echo "<div style=\"color:red\">File with same name cannot be uploaded twice.</div>";
  elseif($captcha != $code)
    echo "<div style=\"color:red\">You've not entered a valid captcha code.</div>";
  else{
	if($captcha == $code){
		$stmt = $con->prepare("CREATE TABLE IF NOT EXISTS $table(".
	"`name` VARCHAR($nmax), `username` VARCHAR($umax), ".
	"`password` VARCHAR(32), `email` VARCHAR(255), ".
	"image VARCHAR(255),".
	"INDEX(name(6)), INDEX(username(6)), INDEX(email(6)))");
	$is_created = $stmt->execute();
	if($is_created){
		$stmt= $con->prepare("SELECT `username` FROM $table WHERE `username` = ? ");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->bind_result($fetched_username);
		$stmt->fetch();
		$stmt->close();
		if($fetched_username == NULL and !$is_uploaded_once){
			$stmt = $con->prepare("INSERT INTO $table VALUES(?,?,?,?,?)");
			$stmt->bind_param('sssss', $name, $username, $password_salted, $email, $name_on_client);
			$is_inserted = $stmt->execute();
			$stmt->close();
			if($is_inserted){ echo "<div style=\"color:green\">Your information inserted successfully.</div>";
			  move_uploaded_file($name_on_server, 'valid_files/'.$name_on_client);
			}else echo "<div style=\"color:red\">Your information cannot be inserted.</div>";

		}else echo "<div style=\"color:red\">This username already exists.</div>";
	}else echo "<div style=\"color:red\">Table cannot be created.</div>";
  }  
}

}

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<title>Captcha using PHP</title>
 	<style>
		input, button{
			padding: 10px;
			outline: none;
			background: none;
			text-align:center;
			font-weight: bold;
		}
	</style>
 </head>
 <body bgcolor="#efefef">
  	<form method="POST" enctype="multipart/form-data" action="">
		<label for="name">Name: </label>
		<input style="margin-left: 105px;" type="text" name="name" id="name" placeholder="Enter name" value="<?php if(isset($name)) echo $name; ?>"><br><br>
		<label for="username">Username: </label>
		<input style="margin-left: 80px;" type="text" name="username" id="username" placeholder="Username" value="<?php if(isset($username)) echo $username; ?>" ><br><br>
		<label for="password">Password: </label>
		<input style="margin-left: 83px;" type="password" name="password" id="password" placeholder="Password" value="<?php if(isset($password)) echo $password; ?>"><br><br>
		<label for="confirm_password">Re-Password: </label>
		<input style="margin-left: 61px;" type="password" name="confirm_password" id="confirm_password" placeholder="Re-password" value="<?php if(isset($confirm_password)) echo $confirm_password; ?>"><br><br>
		<label for="email">Email:</label>
		<input style="margin-left: 105px;" type="email" name="email" id="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>"><br><br>
		<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
		<label for="userfile">Your Photo: </label>
		<input type="file" name="userfile" id="userfile"><br>
 		<!-- <label for=""><span>Captcha:</span> </label> -->
		<?php
		$stmt = $con->prepare("SELECT `id`, `image` FROM captcha_tbl ORDER BY RAND() LIMIT 1");
		$stmt->execute();
		$stmt->bind_result($id, $image);
		$stmt->fetch();
		$captcha_pic = "<img src=\"captcha/$image\">";
		echo $captcha_pic;
		?>
 		<input type="hidden" name="id" value="<?=$id?>">
		<?php
		$stmt->close();
		$con->close();
		?>
 		<input style="border: none; font-size:30px;font-weight:bold; font-style:italic;" type="text" name="captcha" width="10" id="captcha" placeholder=" Captcha code here" autocomplete="off" value="<?php if(isset($captcha)) echo $captcha; ?>"><br><br>
 	 	<button type="submit" name="captcha_btn">Submit</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<button type="reset">Reset</button>
 	</form>
 </body>
 </html>