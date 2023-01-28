<?php 
require_once("connect.php");
if($con->connect_error) die("Connection failed: ");

$success_message = $error_message = "";

if(isset($_POST['captcha_btn'])){
	$captcha = $_POST['captcha'];
	$id = $_POST['id'];

	$stmt = $con->prepare("SELECT `code` FROM captcha_tbl WHERE `id` = ? ");
	$stmt->bind_param('i', $id);
	$stmt->execute();

	$stmt->bind_result($code);
	$stmt->fetch();

	if($captcha === $code) 
		$success_message = "<div class=\"success\">You've not entered a valid captcha code.</div>";
	else
		$error_message = "<div class=\"error\">You've not entered a valid captcha code.</div>";
$stmt->close();

}

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<title>Captcha using PHP</title>
 	<style>
 		.success{
 			color: green;
 		}
 		.error{
 			color: red;
 		}
 	</style>
 </head>
 <body>
 	<?= $success_message ?>
 	<?= $error_message ?>
  	<form method="POST" enctype="multipart/form-data" action="">
 		<label for=""><span>Captcha:</span> </label>
 		<?php 
 		$stmt = $con->prepare("SELECT `id`,`image` FROM  captcha_tbl ORDER BY RAND() LIMIT 1 ");
 		$stmt->execute();
 		$stmt->bind_result($id, $image);
 		$stmt->fetch();
 		$cap_picture = "<img src=\"captcha/$image\">";
 		echo $cap_picture;
 		 ?>
 		<input type="text" name="captcha" width="10" id="captcha" placeholder="Captcha code" autocomplete="off">
 		<input type="text" name="id" value="<?= $id ?>">
 	 	<button type="submit" name="captcha_btn">Submit</button>
 	</form>
 </body>
 </html>