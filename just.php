<?php
if(isset($_POST['submit'])){
    var_dump($_POST);
    var_dump($_FILES);
}


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="userfile" id="userfile">
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>