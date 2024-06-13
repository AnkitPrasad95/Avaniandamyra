<?php

if(isset($_POST['submit']))
{
    //C:\xampp\htdocs\avaniamyra\assets\test_image
   //This is not a good file upload code sample. You have to improve it.
   $image=$_FILES["pic"]["tmp_name"];
   $imageName = $_FILES["pic"]["name"];
   $res = move_uploaded_file($image,'assets/test_image/'.$imageName);
   var_dump($res);
}
?>
<form action="" method="post" enctype="multipart/form-data">
topic image: <input type="file" name="pic" accept="image/*">
<input type="submit" name="submit" value="submit">
</form>

