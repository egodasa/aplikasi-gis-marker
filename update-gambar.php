<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  require_once("config/database.php");
  if($_FILES['update_gambar']['size'] > 300000){
      header("Location: index.php?file_update=besar");
  }else{
    $f = $_FILES['update_gambar']['name'];
    if($f != 0 || empty($f) == false){
      $path = "gambar/";
      $path = $path.basename($_POST['nm_gambar']);
      move_uploaded_file($_FILES['update_gambar']['tmp_name'], $path);
      header("Location: index.php");
    }else{
      header("Location: index.php?file_update=err");
    }
  }
}else{
  echo "Anda dilarang mengakses halaman ini!";
}
?>
