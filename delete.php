<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  require "config/database.php";
  $id_tempat = $_POST['id_tempat'];
  $db->from('tbl_tempat')->where('id_tempat', $id_tempat)->delete()->execute();
  header('Location: index.php?delete=true');
}
else{
  echo "Anda dilarang mengakses halaman ini!";
}
?>

