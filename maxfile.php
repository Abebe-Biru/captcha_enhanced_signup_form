<?php 

if(isset($_POST['submit'])){
	$name_on_client = $_FILES['userfile']['name'];
	$name_on_server = $_FILES['userfile']['tmp_name'];
	$error_value = $_FILES['userfile']['error'];
	$file_type = $_FILES['userfile']['type'];

$type_explode_array = explode('/', $file_type);
$file_extension = end($type_explode_array);
$valid_extensions_array = ['1'=>'jpeg', 'png'];
$is_valid = array_search($file_extension, $valid_extensions_array);

$uploaded_file_array = scandir('valid_files');
$is_there = array_search($name_on_client, $uploaded_file_array);

if($is_valid != FALSE and $is_there == FALSE){
	if($is_valid == 1) echo "<div style=\"color:green\">You choosed jpeg file.</div>";
	else echo "<div style=\"color:green\">You choosed png file.</div>";
}

if($error_value === 4){
		 echo "<div style=\"color:red;\">Choose file before trying to submit!</div>";
	}elseif($error_value === 2){ 
		echo "<div style=\"color:red;\">File should not be of size more than 1MB!</div>";
	}elseif($is_valid == FALSE){ 
    	echo "<div style=\"color:red;\">File should not be different from jpeg or png!</div>";
	}elseif($is_there){
    	echo "<div style=\"color:red;\">File with the same name exists in the directory. Choose another.</div>";
	}else{
		$is_there_directory = move_uploaded_file($name_on_server, 'valid_files/'.$name_on_client);
		if($is_there_directory)
		   echo "<div style=\"color:green\">File moved from server to the choosed directory successfully.</div>";
		else echo "<div style=\"color:red\">I could find the choosed directory to move file.</div>"; 			
	}
}

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<title>File Validation </title>
 </head>
 <body>
 	<form enctype="multipart/form-data" method="POST" action="">
 		<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
 		<input type="file" name="userfile">
 		<input type="submit" name="submit" value="Send File">
 	</form>
 </body>
 </html>